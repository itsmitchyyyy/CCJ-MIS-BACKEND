<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request)
    {
        $data = $request->validated();

        $data['sent_at'] = now();

        if ($request->has('attachment')) {
            $attachments = [];
            foreach ($request->file('attachment') as $attachment) {
                $attachments[] = $attachment->store('message_attachments');
            }
            $data['attachment'] = $attachments;
        }

        $message = Message::create($data);
        return response()->json($message, 201);
    }

    public function index(Request $request)
    {
        $messages = Message::when($request->has('to_id'), function ($query) use ($request) {
            return $query->where('to_id', $request->to_id);
        })->when($request->has('send_from_id'), function ($query) use ($request) {
            return $query->where('send_from_id', $request->send_from_id);
        })
        ->orderBy('sent_at', 'desc')
        ->get();

        MessageResource::withoutWrapping();
        return MessageResource::collection($messages);
    }
}
