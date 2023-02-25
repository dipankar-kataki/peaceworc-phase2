<?php

namespace App\Http\Controllers\Agency\Payments;

use App\Http\Controllers\Controller;
use App\Models\AgencyPayment;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AgencyPaymentController extends Controller
{
    use ApiResponse;

    // public function savePaymentDetails(Request $request){
    //     $validator = Validator::make($request->all(),[
    //         'job_id' => 'required',
    //         'amount' => 'required',
    //         'customer_id' => 'required',
    //         'caregiver_charge' => 'required',
    //         'peaceworc_percentage' => 'required',
    //         'peaceworc_charge' => 'required',
    //         'payment_status' => 'required'
    //     ]);

    //     if($validator->fails()){
    //         $this->error('Oops! Failed To Save Payment Details'.$validator->errors()->first(), null, null, 500);
    //     }else{
    //         try{

    //             $payment_status = '';

    //             if($request->payment_status == 'Success' || $request->payment_status == 'success' || $request->payment_status == 'SUCCESS'){
    //                 $payment_status = 1;
    //             }else{
    //                 $payment_status = 0;
    //             }


    //             $create = AgencyPayment::create([
    //                 'agency_id' => Auth::user()->id,
    //                 'job_id' => $request->job_id,
    //                 'amount' => $request->amount,
    //                 'customer_id' => $request->customer_id,
    //                 'caregiver_charge' => $request->caregiver_charge,
    //                 'peaceworc_percentage' => $request->peaceworc_percentage,
    //                 'peaceworc_charge' => $request->peaceworc_charge,
    //                 'payment_status' => $payment_status
    //             ]);

    //             if($create){
    //                 if($payment_status == 1){

    //                     AgencyPostJob::where('id', $request->job_id)->update([
    //                         'payment_status' => $payment_status
    //                     ]);
                        
    //                     return $this->success('Great! Payment details saved successfully.', null, null, 201);
    //                     // return response()->json(['message' => 'Payment Successfull']);
    //                 }else{
    //                     return $this->success('Payment details saved successfully', null, null, 201);
    //                     // return response()->json(['message' => 'Payment Successfull']);
    //                 }
    //             }
    //         }catch(\Exception $e){
    //             $this->error('Oops! Something Went Wrong. Failed To Save Payment Details', null, null, 500);
    //         }
    //     }
    // }

    public function updateStatus(Request $request){
        // $validator = Validator::make($request->all(),[
        //     'job_id' => 'required',
        //     // 'amount' => 'required',
        //     // 'customer_id' => 'required',
        //     // 'caregiver_charge' => 'required',
        //     // 'peaceworc_percentage' => 'required',
        //     // 'peaceworc_charge' => 'required',
        //     'payment_status' => 'required'
        // ]);

        // if($validator->fails()){
        //     $this->error('Oops! Failed To Save Payment Details'.$validator->errors()->first(), null, null, 500);
        // }else{
        //     try{

                // $create = AgencyPayment::create([
                //     'agency_id' => Auth::user()->id,
                //     'job_id' => $request->job_id,
                //     'amount' => $request->amount,
                //     'customer_id' => $request->customer_id,
                //     'caregiver_charge' => $request->caregiver_charge,
                //     'peaceworc_percentage' => $request->peaceworc_percentage,
                //     'peaceworc_charge' => $request->peaceworc_charge,
                //     'payment_status' => $request->payment_status
                // ]);

                // if($create){
                    // if($request->payment_status == 1){

                        AgencyPostJob::where('id', $request->job_id)->update([
                            'payment_status' => 1
                        ]);
                        
                        return $this->success('Great! Payment details saved successfully.', null, null, 201);
                        // return response()->json(['message' => 'Payment Successfull']);
                    // }
                    // else{
                    //     return $this->success('Payment details saved successfully', null, null, 201);
                    //     // return response()->json(['message' => 'Payment Successfull']);
                    // }
                // }
            // }catch(\Exception $e){
            //     $this->error('Oops! Something Went Wrong. Failed To Save Payment Details', null, null, 500);
            // }
        // }
    }
}
