<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function store(StoreAnnouncementRequest $request)
    {
        $data = $request->validated();

        $data['posted_at'] = now();

        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('announcements');
            }
            $data['images'] = $images;
        }

        $announcement = Announcement::create($data);
        return response()->json($announcement, 201);
    }
}
