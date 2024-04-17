<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFacilityRequest;
use App\Models\Facility;

class FacilityController extends Controller
{
   public function store(StoreFacilityRequest $request)
   {
        $data = $request->validated();
        $facility = Facility::create($data);
        return response()->json($facility, 201);
   }
}
