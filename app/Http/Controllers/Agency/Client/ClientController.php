<?php

namespace App\Http\Controllers\Agency\Client;

use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Models\ClientProfile;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    use ApiResponse;


    public function getProfile(){
        try{
            $get_clients = ClientProfile::where('agency_id', Auth::user()->id)->where('status', 1)->orderBy('created_at', 'DESC')->get();
            return $this->success('Great! Clients Fetched Successfully.', $get_clients, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
    }


    public function createProfile(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'gender' => 'required',
            'age' => 'required',
            'address' => 'required',
            'short_address' => 'required',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{

                $imageName = '';

                if ($request->hasFile('photo')) {
                    $image = time() . '.' . $request->photo->extension();
                    $request->photo->move(public_path('Agency/Uploads/Client/Profile/Image/'), $image);
                    $imageName = 'Agency/Uploads/Client/Profile/Image/' . $image;
                }
                
                $check_phone_exists = ClientProfile::where('phone', $request->phone)->exists();
                $check_email_exists = ClientProfile::where('email', $request->email)->exists();
                if($check_phone_exists){
                    return $this->error('Oops! Phone Number Already Exist.', null, null, 400);
                }else if($check_email_exists){
                    return $this->error('Oops! Email Already Exist.', null, null, 400);
                }else{

                    ClientProfile::create([
                        'agency_id' => Auth::user()->id,
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'gender' => $request->gender,
                        'age' => $request->age,
                        'address' => $request->address,
                        'photo' => $imageName,
                        'short_address' => $request->short_address,
                        'street' => $request->street,
                        'appartment_or_unit' => $request->appartment_or_unit,
                        'floor_no' => $request->floor_no,
                        'city' => $request->city,
                        'state' => $request->state,
                        'zip_code' => $request->zip_code,
                        'country' => $request->country,
                        'lat' => $request->lat,
                        'long' => $request->long,
                    ]);

                    
                    $client_data = ClientProfile::where('status', 1)->where('email', $request->email)->first();
                    $details = [];
                    $client_details = [
                        'client_id' => $client_data->id,
                        'agency_id' => $client_data->agency_id,
                        'name' => $client_data->name,
                        'phone' => $client_data->phone,
                        'email' => $client_data->email,
                        'gender' => $client_data->gender,
                        'age' => $client_data->age,
                        'address' => $client_data->address,
                        'photo' => $client_data->photo,
                        'short_address' => $client_data->short_address,
                        'street' => $client_data->street,
                        'appartment_or_unit' => $client_data->appartment_or_unit,
                        'floor_no' => $client_data->floor_no,
                        'city' => $client_data->city,
                        'state' => $client_data->state,
                        'zip_code' => $client_data->zip_code,
                        'country' => $client_data->country,
                        'lat' => $client_data->lat,
                        'long' => $client_data->long,
                    ];

                    array_push($details, $client_details);

    
                    return $this->success('Great! Client Profile Created Successfully.', $details, null, 201);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }


    public function searchClient(Request $request){
        try{
            if(!isset($_GET['client_name'])){

                $get_clients = ClientProfile::where('agency_id', Auth::user()->id)
                                ->where('status', 1)
                                ->get();
                return $this->success('Great! Details Fetched Successfully', $get_clients, null, 200);
            }else{
                $client_name = $_GET['client_name'];
                

                $get_clients = ClientProfile::where('agency_id', Auth::user()->id)
                                ->where('status', 1)
                                ->where('name','LIKE','%'.$client_name.'%')
                                ->get();
                return $this->success('Great! Details Fetched Successfully', $get_clients, null, 200);
            }
            
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null ,null, 500);
        }
    }


    public function deleteClient(Request $request){
        $validator = Validator::make($request->all(),[
            'client_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $check_if_client_exist_in_job_table = AgencyPostJob::where('client_id', $request->client_id)->exists();
                if($check_if_client_exist_in_job_table){
                    return $this->error('Oops! This client cannot be deleted as it is associated with a job.', null, null, 400);
                }else{
                    ClientProfile::where('id', $request->client_id)->update([
                        'status' => 0
                    ]);
    
                    return $this->success('Great! Client Deleted Successfully.', null, null, 200);
                }
                
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }
}
