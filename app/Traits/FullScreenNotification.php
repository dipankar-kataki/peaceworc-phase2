<?php

namespace App\Traits;

trait FullScreenNotification{

    protected function sendNotification(Array $token, Array $data){

        $server_key = env('FIREBASE_SERVER_KEY');
            
        $msg = [
            'message'   => $data['message'],
        ];

        $notify_data = [
            'body' => $data['message'],
            'title' => 'Peaceworc'
        ];

        $registrationIds = $token;
            
        if(count($token) > 1){
            $fields = array
            (
                'registration_ids' => $registrationIds, //  for  multiple users
                'notification'  => $notify_data,
                'data'=> [],
                'priority'=> 'high'
            );
        }
        else{
            
            $fields = array
            (
                'to' => $registrationIds[0], //  for  only one users
                'notification'  => $notify_data,
                'data'=> [
                    'job_id' => $data['job_id'],
                    'job_title' => $data['job_title'],
                    'job_amount' => $data['job_amount'],
                    'job_start_date' => $data['job_start_date'],
                    'job_start_time' => $data['job_start_time'],
                    'job_end_date' => $data['job_end_date'],
                    'job_end_time' =>  $data['job_end_time'], 
                    'care_type' =>  $data['care_type'],
                    'care_items' =>  $data['care_items'],
                    'notification_type' => $data['notification_type']
                ],
                'priority'=> 'high'
            );
        }
            
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $server_key;

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        // curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        if ($result === FALSE) 
        {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close( $ch );
        return $result;
    }  
}
