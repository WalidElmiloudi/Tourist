<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Itinerary;

class ItineraryController extends Controller
{
    public function index(Request $request)
    {
        $query = Itinerary::query();

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by duration
        if ($request->has('duration')) {
            $query->where('duration', '<=', $request->duration);
        }

        // Search by title
        if ($request->has('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        $itineraries = $query->with('destinations')->get();

        return response()->json($itineraries);
    }

    public function show($id)
    {
        $itinerary = Itinerary::with('destinations')->findOrFail($id);

        return response()->json($itinerary);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'duration' => 'required|integer',
            'image' => 'required',

            'destinations' => 'required|array|min:2',
            'destinations.*.name' => 'required',
            'destinations.*.accommodation' => 'required',

            'destinations.*.activities' => 'required|array|min:1',
            'destinations.*.activities.*.description' => 'required'
        ]);

        $itinerary = DB::transaction(function () use ($request) {

            $itinerary = Itinerary::create([
                'title' => $request->title,
                'category' => $request->category,
                'duration' => $request->duration,
                'image' => $request->image,
                'user_id' => auth()->id()
            ]);

            foreach ($request->destinations as $destinationData) {

                $destination = $itinerary->destinations()->create([
                    'name' => $destinationData['name'],
                    'accommodation' => $destinationData['accommodation']
                ]);

                if (isset($destinationData['activities'])) {

                    foreach ($destinationData['activities'] as $activityData) {

                        $destination->activities()->create([
                            'description' => $activityData['description']
                        ]);
                    }
                }
            }

            return $itinerary->load('destinations.activities');
        });

        return response()->json([
            'message' => 'Itinerary created successfully',
            'itinerary' => $itinerary->load('destinations')
        ], 201);
    }
}
