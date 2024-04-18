<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFacilityRequest;
use App\Http\Requests\StoreRequestFacilityRequest;
use App\Http\Resources\FacilityResource;
use App\Http\Resources\RequestFacilityResource;
use App\Models\Facility;
use App\Models\RequestFacility;

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

   public function createFacilityRequest(StoreRequestFacilityRequest $request, Facility $facility)
   {
          $data = $request->validated();

          if ($request->has('reservation_date')) {
                $data['reservation_date'] = date('Y-m-d', strtotime($data['reservation_date']));
          }

          if ($request->has('borrowed_date')) {
                $data['borrowed_date'] = date('Y-m-d', strtotime($data['borrowed_date']));
          }
          
          $facility->requests()->create($data);

          return response()->json(['message' => 'Request submitted successfully'], 201);
   }

   public function fetchFacilityRequests(Request $request)
   {
        $facilityRequests = RequestFacility::when($request->status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->when($request->reservation_date, function ($query, $reservationDate) {
            return $query->where('reservation_date', $reservationDate);
        })
        ->when($request->borrowed_date, function ($query, $borrowedDate) {
            return $query->where('borrowed_date', $borrowedDate);
        })
        ->when($request->returned_date, function ($query, $returnedDate) {
            return $query->where('returned_date', $returnedDate);
        })
        ->when($request->user_id, function ($query, $userId) {
            return $query->where('user_id', $userId);
        })
        ->when($request->facility_id, function ($query, $facilityId) {
            return $query->where('facility_id', $facilityId);
        })
        ->get();

        return new RequestFacilityResource($facilityRequests);
   }
}
