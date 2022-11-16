<?php

namespace App\Http\Controllers\Agency\Job;

use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostJobController extends Controller
{
    use ApiResponse;

    public function createJob(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required | string',
            'care_type' => 'required | string',
            'care_items' => 'required',
            'date' => 'required',
            'time' => 'required',
            'amount' => 'required',
            'address' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return $this->error('Oops! Validation Failed. '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $create = AgencyPostJob::create([
                    'user_id' => Auth::user()->id,
                    'title' => $request->title,
                    'care_type' => $request->care_type,
                    'care_items' => json_encode($request->care_items),
                    'date' => $request->date,
                    'time' => $request->time,
                    'amount' => $request->amount,
                    'address' => $request->address,
                    'description' => $request->description,
                    'medical_history' => json_encode($request->medical_history),
                    'experties' => json_encode($request->experties),
                    'other_requirements' => json_encode($request->other_requirements),
                    'check_list' => json_encode($request->check_list)
                ]);
                if($create){
                    return $this->success('Great! Job Posted Successfully', null, null, 201);
                }else{
                    return $this->error('Oops! Something Went Wrong.', null, null, 500);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }
}
