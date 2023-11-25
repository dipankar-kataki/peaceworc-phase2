<?php

namespace App\Traits;

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
|Job Distance Trait
|--------------------------------------------------------------------------
|
| This trait will be used to calculate distance between job and caregiver.
|
*/

trait JobDistance
{

	protected function getJobDistance($lat1, $long1, $lat2, $long2, $unit)
	{
		if (($lat1 == $lat2  ) && ($long1 == $long2 )) {
            return 0;
        }else {
            $theta = $long1 - $long2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
    
            if ($unit == "K") {
                return ( round($miles * 1.609344, 2) ).' '.'Kilometers';
            } else if ($unit == "N") {
                return ( round($miles * 0.8684, 2) ).' '.'Nautical Miles';
            } else {
                return round($miles, 2);
            }
        }
	}

}