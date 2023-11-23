<?php

namespace App\Http\Controllers\Agency\Notification;

use App\Http\Controllers\Controller;
use App\Models\AgencyNotification;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AgencyNotificationController extends Controller
{
    use ApiResponse;
    public function getUnreadNotification(){
        try{
            $get_unread_notifications = AgencyNotification::where('user_id', Auth::user()->id)->where('mark_as_read', 0)->get();
            $all_details = [];
            if(!$get_unread_notifications->isEmpty()){
                foreach($get_unread_notifications as $notification){
                    $details = [
                        'notification_id' => $notification->id,
                        'content' => $notification->content,
                        'type' => $notification->type,
                        'created_at' => Carbon::parse($notification->created_at)->format('M d, h:i A')
                    ];

                    array_push($all_details, $details);
                }

                return $this->success('Great! Notifications fetched successfully.', $all_details, null, 200);

                
            }else{
                return $this->success('You\'re all caught up! There are currently no new notifications at the moment.', null, null, 200);
            }
        }catch(\Exception $e){
            return $this->error('Oops! Something went wrong. Failed to fetch unread notifications.', null, null, 500);
        }
    }

    public function markNotificationAsRead(Request $request){
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $check_if_notification_exists = AgencyNotification::where('id', $request->notification_id)->where('user_id', Auth::user()->id)->exists();

                if(!$check_if_notification_exists){
                    return $this->error('Oops! Failed to mark as read. Notification does not exists.', null, null, 400);
                }else{
                    AgencyNotification::where('id', $request->notification_id)->where('user_id', Auth::user()->id)->update([
                        'mark_as_read' => 1
                    ]);

                    return $this->success('Great! Notification marked as read.', null, null, 200);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something went wrong.', null, null, 500);
            }
        }
    }

    public function getUnreadNotificationCount(){
        try{
            $get_unread_notification_count = AgencyNotification::where('user_id', Auth::user()->id)->where('mark_as_read', 0)->count();
            return $this->success('Great! Unread notification counts fetched successfully.', $get_unread_notification_count, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something went wrong.', null, null, 500);
        }
    }
}
