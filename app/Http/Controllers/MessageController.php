<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMessageRequest;
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
}
