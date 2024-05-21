<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessageThreadResource;
use App\Models\User;
use App\Models\Message;
use App\Models\Notification;
use App\Models\MessageThread;
use App\Events\SendMessage;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request)
    {
        $data = $request->validated();

        $data['sent_at'] = now();

        if ($request->has('message_thread_id')) {
            $messageThread = MessageThread::find($data['message_thread_id']);
        } else {
            $messageThread = MessageThread::where('user_one_id', $data['to_id'])
            ->where('user_two_id', $data['send_from_id'])
            ->orWhere(function ($query) use ($data) {
                $query->where('user_one_id', $data['send_from_id'])
                    ->where('user_two_id', $data['to_id']);
            })
            ->where('subject', $data['subject'])
            ->first();
        }

        if (!$messageThread) {
            $messageThread = MessageThread::create([
                'user_one_id' => $data['to_id'],
                'user_two_id' => $data['send_from_id'],
                'subject' => $data['subject'],
            ]);
        }            

        if ($request->has('attachment')) {
            $attachments = [];
            foreach ($request->file('attachment') as $attachment) {
                $attachments[] = $attachment->store('message_attachments');
            }
            $data['attachment'] = $attachments;
        }

        $data['message_thread_id'] = $messageThread->id;
        $message = Message::create($data);
        
        $unreadCount = $messageThread->unread_count + 1;
        $messageThread->update([
            'last_message_at' => now(),
            'last_message_id' => $message->id,
            'unread_count' => $unreadCount,
        ]);

        if (!$request->has('message_thread_id')) {
            $user = User::find($data['send_from_id']);
            $notification = Notification::create([
                'user_id' => $data['to_id'],
                'message' => "{$user['first_name']} {$user['last_name']} has sent you a new message",
                'event' => 'send.message',
                'url' => '/messages/inbox/' . $messageThread->id,
            ]);

            if ($notification) {
                broadcast(new SendMessage($messageThread->id, $user));
            }
        }
       
        return response()->json($message, 201);
    }

    public function index(Request $request)
    {

        if ($request->has('message_thread_id')) {
            $messages = Message::where('message_thread_id', $request->message_thread_id)
                ->orderBy('sent_at', 'desc')
                ->get();

            MessageResource::withoutWrapping();
            return MessageResource::collection($messages);
        }

        if (!$request->has('to_id') ) {
            return response()->json(['error' => 'to_id is required'], 400);
        }

        $messageThreadIds = MessageThread::where('user_one_id', $request->to_id)
            ->orWhere('user_two_id', $request->to_id)
            ->pluck('id')
            ->toArray();

        $messages = Message::whereIn('message_thread_id', $messageThreadIds)
            ->orderBy('sent_at', 'desc')
            ->get();

        if ($request->has('isGroup')) {
            $messages = $messages->groupBy('message_thread_id')->map(function ($message) {
                return $message->first();
            });
        }

        MessageResource::withoutWrapping();
        return MessageResource::collection($messages);
    }

    public function markAsRead(Message $message)
    {
        $message->update([
            'read_at' => now(),
            'status' => 'read',
        ]);

        $messageThread = MessageThread::find($message->message_thread_id);
        $unreadCount = $messageThread->unread_count - 1;
        $messageThread->update([
            'unread_count' => $unreadCount,
        ]);

        return response()->json($message, 200);
    }

    public function getMessageThread($id)
    {
        $messageThread = MessageThread::find($id);

        if (!$messageThread) {
            return response()->json(['error' => 'Message thread not found'], 404);
        }

        $messageThread->load('messages');

        MessageThreadResource::withoutWrapping();
        return new MessageThreadResource($messageThread);
    }
}
