<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\NotificationsResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function allNotifications() {
        return new NotificationsResource(auth()->user()->notifications);
    }

    public function markNotificationsAsRead() {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['message'=>__('apiMessage.markNotificationsAsRead')], 200);
    }


    public function sendPushNotification($registatoin_ids, $notification, $device_type)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        if ($device_type == "Android") {
            $fields = array(
                'to' => $registatoin_ids,
                'data' => $notification,

            );
        } else {
            $fields = array(
                'to' => $registatoin_ids,
                'notification' => $notification
            );
        }
        // Firebase API Key
        $headers = array('Authorization:key=AAAAOLlqjys:APA91bF2AkwKeJyUzMv2JCtG4RPHAjyU13C7_AbMIvfAkmLZxJEOo8z6mZoPyj2luHe1D8g8g2meMkt1SPipBL6IP0Ibth2aNsgL9XpeJf6KSJwyXWVQ17j0qCTTAjRByMkdT0ObJ5Gb', 'Content-Type:application/json');
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}
