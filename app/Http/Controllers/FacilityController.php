<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFacilityRequest;
use App\Http\Requests\StoreRequestFacilityRequest;
use App\Http\Requests\UpdateFacilityRequest;
use App\Http\Requests\UpdateRequestFacility;
use App\Http\Resources\FacilityResource;
use App\Http\Resources\RequestFacilityResource;
use App\Models\Facility;
use App\Models\RequestFacility;
use App\Enums\RequestFacilityStatus;
use App\Enums\FacilityStatus;
use App\Enums\EquipmentStatus;
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
   
   public function update(UpdateFacilityRequest $request, Facility $facility)
   {
        $data = $request->validated();

        if ($request->has('request_id')) {
            RequestFacility::where('id', $data['request_id'])->update(['equipmentStatus' => $data['equipmentStatus']]);
        
            if ($data['equipmentStatus'] === EquipmentStatus::Lost->value || $data['equipmentStatus'] === EquipmentStatus::Badly->value) {
                $data['status'] = FacilityStatus::Unavailable;
            }
        }

        $facility->update([
            'status' => $data['status']
        ]);

        return response()->json($facility);
   }

   public function createFacilityRequest(StoreRequestFacilityRequest $request, Facility $facility)
   {
        $data = $request->validated();

        if ($request->has('reservation_date')) {
            $data['reservation_date'] = date('Y-m-d', strtotime($data['reservation_date']));
            $data['reservation_time'] = date('H:i:s', strtotime($data['reservation_time']));
        }

        if ($request->has('borrowed_date')) {
            $data['borrowed_date'] = date('Y-m-d', strtotime($data['borrowed_date']));
        }

        $facilityRequest = RequestFacility::where('facility_id', $facility->id)
            ->where('user_id',$data['user_id'])
            ->where('status', RequestFacilityStatus::Pending)
            ->first();

        if ($facilityRequest) {
            $facilityRequest->update($data);
            return response()->json(['message' => 'Request updated successfully']);
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
        } else if ($data['status'] === RequestFacilityStatus::Approved->value && $requestFacility->status !== RequestFacilityStatus::Approved) {
            $data['approved_by'] = auth()->id();
            $data['approved_date'] = now();

            $requestFacility
                ->facility()
                ->update(['status' => FacilityStatus::Booked]);
        } else if (!$request->has('returned_date') && ($data['status'] === RequestFacilityStatus::Rejected->value || $data['status'] === RequestFacilityStatus::Cancelled->value) && $requestFacility->status !== RequestFacilityStatus::Rejected) {
            $requestFacility
                ->facility()
                ->update(['status' => FacilityStatus::Available]);
        }

        $requestFacility->update($data);

        return response()->json(['message' => 'Request updated successfully']);
   }
}
