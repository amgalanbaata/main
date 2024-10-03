<?php

namespace App\Http\Controllers;

use App\Models\Model\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $colorName = [
            'yellow' => 'шар',
            'red' => 'улаан',
            'green' => 'ногоон',
        ];

        $locations = Location::all();

        foreach ($locations as $location) {
        $location->color = $colorName[$location->color] ?? $location->color;
    }
        return view('admin.user.location', compact('locations'));
    }

    public function create()
    {
        return view('admin.user.addLocation');
    }

    public function store(Request $request)
    {
        $model = new Location;
        $data = ($request);

        $data->validate([
            'title' => 'required|string|max:255',
            'comment' => 'nullable|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'color' => 'required|string',
        ]);

        $post = [
            'title' => $request->title,
            'comment' => $request->comment,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'color' => $request->color,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // $model->locationSave($post);

        Location::create($data->all());

        return redirect()->route('locations.index')->with('success', 'Location added successfully.');
    }

    public function edit(Location $location)
    {
        return view('admin.user.locationEdit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'comment' => 'nullable|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'color' => 'required|string',
        ]);

        $location->update($request->all());

        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }
}
