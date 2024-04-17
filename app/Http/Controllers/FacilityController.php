<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFacilityRequest;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;

class FacilityController extends Controller
{
   public function store(StoreFacilityRequest $request)
   {
        $data = $request->validated();
        $facility = Facility::create($data);
        return response()->json($facility, 201);
   }

   public function index(Request $request)
   {
        $facilities = Facility::when($request->type, function ($query, $type) {
            return $query->where('type', $type);
        })
        ->when($request->status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->get();

        FacilityResource::withoutWrapping();
        return FacilityResource::collection($facilities);
   }

   public function destroy(Facility $facility)
   {
        $facility->delete();
        return response()->noContent();
   }
}
