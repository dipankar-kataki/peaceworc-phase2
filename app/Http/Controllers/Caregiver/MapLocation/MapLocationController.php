<?php

namespace App\Http\Controllers\Caregiver\MapLocation;

use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use App\Traits\JobDistance;
use Illuminate\Http\Request;

class MapLocationController extends Controller
{
    use ApiResponse, JobDistance;

    public function getJobLocations(){
        if(!isset($_GET['current_lat']) &&  !isset($_GET['current_long'])){
            return $this->error('Oops! Current Latitude and Current Longitude Required', null, null, 400);
        }else{
            $get_jobs = AgencyPostJob::where('payment_status', 1)->get();
            $get_locations = [];

            foreach($get_jobs as $jobs){
                $lat2 = $jobs->lat;
                $long2 = $jobs->long;
                $miles = $this->jobDistance($_GET['current_lat'], $_GET['current_long'], $lat2, $long2, 'M');

                if($miles <= 50){

                    $details = [
                        'latitude' => $lat2,
                        'longitude' => $long2
                    ];

                    array_push($get_locations, $details);
                }
                
            }

            return $this->success('Great! Coordinated Fetched Successfully', $get_locations, null, 200);

        }

    }
}
