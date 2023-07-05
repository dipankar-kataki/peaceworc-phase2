<?php

namespace App\Http\Controllers\Chatting;

use App\Http\Controllers\Controller;
use App\Models\ChatSystem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
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
        return $this->success('Great! Message Uploaded Successfully.', $request->data->toArray(), null, 201);
        $validator = Validator::make($request->all(),[
            'sent_by' => 'required',
            'received_by' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                
                ChatSystem::create([
                    'sent_by' => $request->sent_by,
                    'received_by' => $request->received_by,
                    'message' => $request->message,
                    'image_path' => $request->imagePath
                ]);

                return $this->success('Great! Message Uploaded Successfully.', null, null, 201);
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }
}
