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
                return $this->error('Oops! Job id is required', null, null, 500);
            }else{
                if($_GET['job_id'] == 0){
                    return $this->error('Oops! Invalid Job Id', null, null, 400);
                }else{
                    $check_if_job_id_exists = ChatSystem::where('job_id', $_GET['job_id'])->exists();
                    if($check_if_job_id_exists){
                        $chat_details = [];
                        $chat_data = ChatSystem::with('job')->where('job_id', $_GET['job_id'])->where('sent_id', Auth::user()->id)->latest()->paginate(10);

                        foreach($chat_data as $key => $item){
                            $details = [
                                'message_id' => $item->message_id,
                                'message' => $item->message,
                                'image_path' => $item->image_path,
                                'is_message_sent' => $item->is_message_sent,
                                'is_message_seen' => $item->is_message_seen,
                                'job_title' => $item->job->title,
                                'sent_by' => $item->sent_id,
                                'received_by' => $item->received_id
                            ];

                            array_push($chat_details, $details);
                        }
                        
                        return $this->success('Great! Chats Fetched Successfully', $chat_details, null, 200);
                    }else{
                        return $this->error('Oops! Invalid Job Id.', null, null, 400);
                    }
                }
            }
            
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
    }
}
