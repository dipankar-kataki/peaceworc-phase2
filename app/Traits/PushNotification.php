<?php

namespace App\Traits;

trait PushNotification{

    protected function sendNotification(Array $token, Array $data){

        $server_key = 'AAAAFZkv8Dw:APA91bH2u9SjQqjmMoPRZeLWzWE82Rrme0iAIZhqpsxFsNoAw1_dG2_yf2V7ngxi0VgXDlmtKLfqbXv4vlxj6ogzif_ziKW_wdlOKMZq9OdnhCQGW7NBq_h8_eNxRgOXwvlW6o1CYgRd';
            
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
                    'job_start_time' => $data['job_start_time'],
                    'job_end_time' =>  $data['job_end_time'], 
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
