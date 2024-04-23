<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use App\Enums\AnnouncementStatus;
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

    public function index(Request $request)
    {
        $announcements = Announcement::where('status', AnnouncementStatus::Active)
            ->orderBy('posted_at', 'desc')
            ->get();

        AnnouncementResource::withoutWrapping();
        return AnnouncementResource::collection($announcements);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return response()->noContent();
    }
}
