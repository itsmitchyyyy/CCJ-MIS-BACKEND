<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('user_id')) {
            $notifications = Notification::where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        } else {
            $notifications = Notification::orderBy('created_at', 'desc')
                ->get();
        }
        
        NotificationResource::withoutWrapping();
        return NotificationResource::collection($notifications);
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->update($request->all());
            return response()->json($notification, 200);
        } else {
            return response()->json(['message' => 'Notification not found'], 404);
        }
    }
}
