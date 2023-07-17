<?php

namespace App\Http\Controllers\Agency\Chatting;

use App\Http\Controllers\Controller;
use App\Models\ChatSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyChattingController extends Controller
{
    public function getChats(){
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
