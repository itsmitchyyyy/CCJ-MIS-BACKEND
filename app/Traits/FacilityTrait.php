<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\RequestFacility;

trait FacilityTrait {

    public function getFacilityRequests(Request $request) 
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
        ->orderBy('created_at', 'desc')
        ->get();

        return $facilityRequests;
    }
}