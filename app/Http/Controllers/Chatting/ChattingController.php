<?php

namespace App\Http\Controllers\Chatting;

use App\Http\Controllers\Controller;
use App\Models\ChatSystem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChattingController extends Controller
{   
    use ApiResponse;
    public function uploadImage(Request $request){
        $validator = Validator::make($request->all(),[
            'sent_by' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:1048'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $imageName = null;
                if ($request->hasFile('image')) {
                    $image = time() . '.' . $request->image->extension();
                    $request->image->move(public_path('Chatting/Uploads/By-User-'.$request->sent_by.'/Image/'), $image);
                    $imageName = 'Chatting/Uploads/By-User-'.$request->sent_by.'/Image/' . $image;
                }

                return $this->success('Great! Image Uploaded Successfully.', $imageName, null, 201);
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', $e->getMessage(), null, 500);
            }
        }
    }

    public function uploadMessage(Request $request){
        $jsonResponse =  $request->getContent();
        $messageData = json_decode($jsonResponse, true);
        try{

            if( ($messageData['chatResponse']['userId'] == '') || ($messageData['chatResponse']['targetId'] == '')){
                return $this->error('Oops! Message Not Saved. Target-Id or User-Id Might Be Missing', null, null, 400);
            }else{
                $create = ChatSystem::create([
                    'sent_id' => $messageData['chatResponse']['userId'],
                    'received_id' => $messageData['chatResponse']['targetId'],
                    'job_id' => $messageData['chatResponse']['jobId'],
                    'message_id' => $messageData['chatResponse']['messageId'],
                    'message' => $messageData['chatResponse']['msg'],
                    'image_path' => $messageData['chatResponse']['image'],
                    'is_message_sent' => 1,

                ]);

                if($create){
                    return $this->success('Great! Message Uploaded Successfully.', null, null, 201);
                }else{
                    return $this->success('Oops! Message Not Uploaded.', null, null, 400);
                }
            }
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
        
    }

    public function updateMessage(Request $request){
        try{
            $jsonResponse =  $request->getContent();
            $messageData = json_decode($jsonResponse, true);

            ChatSystem::where('message_id', $messageData['messageId'])->update([
                'is_message_seen' => 1
            ]);

            return $this->success('Great! Message Seen Successfully.', null, null, 201);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong. '.$e->getMessage(), null, null, 500);
        }
    }

    public function getChats(Request $request){
        try{
            if(!isset($_GET['job_id'])){
                return response()->json(['success' => false, 'message' => 'Oops! Job id is required', 'chatModel' =>  [], 'token' => null, 'httpStatusCode' => 500]);
            }else{
                if($_GET['job_id'] == 0){
                    return response()->json(['success' => false, 'message' => 'Oops! Invalid Job Id', 'chatModel' =>  [], 'token' => null, 'httpStatusCode' => 400]);
                }else{
                    $check_if_job_id_exists = ChatSystem::where('job_id', $_GET['job_id'])->exists();
                    $chat_details = [];
                    if($check_if_job_id_exists){
                        
                        $chat_data = ChatSystem::with('job')->where('job_id', $_GET['job_id'])->where('sent_id', Auth::user()->id)->latest()->paginate(10);

                        foreach($chat_data as $key => $item){
                            $details = [
                                'messageId' => $item->message_id,
                                'msg' => $item->message,
                                'image' => $item->image_path,
                                'is_message_seen' => $item->is_message_seen,
                                'userId' => $item->sent_id,
                                'targetId' => $item->received_id
                            ];

                            array_push($chat_details, $details);
                        }
                        
                        return response()->json(['success' => true, 'message' => 'Great! Chats Fetched Successfully', 'chatModel' =>  $chat_details, 'token' => null, 'httpStatusCode' => 200]);
                        // return $this->success('Great! Chats Fetched Successfully', $chat_details, null, 200);
                    }else{
                        return response()->json(['success' => true, 'message' => 'Great! Chats Fetched Successfully', 'chatModel' =>  $chat_details, 'token' => null, 'httpStatusCode' => 200]);
                    }
                }
            }
            
        }catch(\Exception $e){
            return response()->json(['success' => false, 'message' => 'Oops! Something Went Wrong', 'chatModel' =>  [], 'token' => null, 'httpStatusCode' => 500]);
        }
    }
}
