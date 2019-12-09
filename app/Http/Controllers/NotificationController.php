<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;


class NotificationController extends Controller
{
    public function index(Request $request){
        $notifications = Notification::where('user_id', $request->query('user_id'))->latest()->simplePaginate(5);
        return response()->json([
            'notifications' => $notifications
        ]);
    }

    public function update(Request $request){
        Notification::where('user_id', $request->user_id)->where('read',false)->update([
            'read' => true
        ]);
        return response()->json([
            'message' => 'updated successfully'
        ]);
    }
}
