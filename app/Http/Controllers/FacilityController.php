<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFacilityRequest;
use App\Http\Requests\StoreRequestFacilityRequest;
use App\Http\Requests\UpdateRequestFacility;
use App\Http\Resources\FacilityResource;
use App\Http\Resources\RequestFacilityResource;
use App\Models\Facility;
use App\Models\RequestFacility;
use App\Enums\RequestFacilityStatus;
use App\Enums\FacilityStatus;
use App\Traits\FacilityTrait;

class FacilityController extends Controller
{
    use FacilityTrait;

   public function store(StoreFacilityRequest $request)
   {
        $data = $request->validated();
        $facility = Facility::create($data);
        return response()->json($facility, 201);
   }

   public function index(Request $request)
   {

        if ($request->has('type') && $request->type === 'my-request') {
            $myRequest = $this->getFacilityRequests($request);

            RequestFacilityResource::withoutWrapping();
            return RequestFacilityResource::collection($myRequest);
        }

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
        $facilityRequests = $this->getFacilityRequests($request);

        RequestFacilityResource::withoutWrapping();
        return RequestFacilityResource::collection($facilityRequests);
   }

   public function updateFacilityRequest(UpdateRequestFacility $request, RequestFacility $requestFacility)
   {
        $data = $request->validated();

        if ($request->has('returned_date') && $requestFacility->status === RequestFacilityStatus::Approved) {
            $data['returned_date'] = date('Y-m-d', strtotime($data['returned_date']));
            $requestFacility
                ->facility()
                ->update(['status' => FacilityStatus::Available]);
        } else if ($data['status'] === RequestFacilityStatus::Approved->value && $requestFacility->status !== RequestFacilityStatus::Approved) {
            $data['approved_by'] = auth()->id();
            $data['approved_date'] = now();

            $requestFacility
                ->facility()
                ->update(['status' => FacilityStatus::Booked]);
        } else if (!$request->has('returned_date') && ($data['status'] === RequestFacilityStatus::Rejected || $data['status'] === RequestFacilityStatus::Cancelled) && $requestFacility->status !== RequestFacilityStatus::Rejected) {
            $requestFacility
                ->facility()
                ->update(['status' => FacilityStatus::Available]);
        }

        $requestFacility->update($data);

        return response()->json(['message' => 'Request updated successfully']);
   }
}
