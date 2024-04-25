<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::first();
        
        return response()->json($settings);
    }

    public function store(Request $request)
    {
        $settings = Settings::first();

        if (!$settings) {
            // Create a new settings record if it doesn't exist
            $settings = Settings::create($request->all());
        } else {
            // Update the existing settings record
            $settings->update($request->all());
        }
        
        return response()->json($settings);
    }
}
