<?php

namespace App\Http\Controllers\Agency\Client;

use App\Http\Controllers\Controller;
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
            'address' => 'required',
            'photo' => 'required|image|mimes:jpg,png,jpeg|max:1048',
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
                
                $get_client = ClientProfile::where('phone', $request->phone)->exists();
                if($get_client){
                    return $this->error('Oops! Phone Number Already Exist.', null, null, 400);
                }else{

                    ClientProfile::create([
                        'agency_id' => Auth::user()->id,
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'email' => $request->email,
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
    
                    return $this->success('Great! Client Profile Created Successfully.', null, null, 201);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.'.$e, null, null, 500);
            }
        }
    }


    public function searchClient(Request $request){
        try{
            if(!isset($_GET['client_name'])){
                return $this->error('Oops! Client Name Required.', null ,null, 400);
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
                ClientProfile::where('id', $request->client_id)->update([
                    'status' => 0
                ]);

                return $this->success('Great! Client Deleted Successfully.', null, null, 200);
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }
}
