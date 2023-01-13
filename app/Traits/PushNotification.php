<?php

namespace App\Traits;

trait PushNotification{

    protected function sendNotification(Array $token, Array $data){

        $server_key = 'AAAAtDUEtQM:APA91bEMkFXn34gPBQy6l1Ys2y0KvHnOyTJmvFqW1gInxBMOSxGKc60Z1HYSPa2LBSGWefCaCqFkNHh29_YtwWXN2Rq_RqhCls0VzalwZ6l7RZORLdJrUU4ZFyp8jnzvkT0zD3D9BkAt';
            
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
                'data'=> $msg,
                'priority'=> 'high'
            );
        }
        else{
            
            $fields = array
            (
                'to' => $registrationIds[0], //  for  only one users
                'notification'  => $notify_data,
                'data'=> $msg,
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
