<?php

namespace App\Traits;

trait WelcomeNotification{

    protected function sendWelcomeNotification(String $token, String $message){

        $server_key = env('FIREBASE_SERVER_KEY');
            
        // $msg = [
        //     'message'   => $data['message'],
        // ];

        $notify_data = [
            'body' => $message,
            'title' => 'Peaceworc'
        ];

        $registrationIds = $token;
            
        // if(count($token) > 1){
        //     $fields = array
        //     (
        //         'registration_ids' => $registrationIds, //  for  multiple users
        //         'notification'  => $notify_data,
        //         'data'=> [],
        //         'priority'=> 'high'
        //     );
        // }
        // else{
            
            $fields = array
            (
                'to' => $registrationIds, //  for  only one users
                'notification'  => $notify_data,
                'data'=> [],
                'priority'=> 'high'
            );
        // }
            
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $server_key;

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
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
