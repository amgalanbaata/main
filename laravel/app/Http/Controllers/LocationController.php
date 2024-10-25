<?php

namespace App\Http\Controllers;

use App\Models\Model\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $model = new Location();
        $colorName = [
            'red' => 'Их',
            'yellow' => 'Дунд',
            'green' => 'Бага',
        ];

        $locations = Location::all();

        foreach ($locations as $location) {
        $location->color = $colorName[$location->color] ?? $location->color;
    }
        $data = $model->getLocation();

        return view('admin.user.location', compact('locations'), ['data' => $data]);
    }

    public function indexPage() {
        return view('admin.resolvedAddLocation');
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

        return redirect()->route('locations.index')->with('success', 'Байршил амжилттай бүртгэгдлээ');
    }

    public function createLocation(Request $request) {

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

        return redirect()->route('locations.index')->with('success', 'Байршил амжилттай шинэчлэгдлээ');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Байршил амжилттай устгагдлаа');
    }
}
