<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index($itineraryId)
    {
        $destinations = Destination::where('itinerary_id', $itineraryId)
            ->with('activities')
            ->get();

        return response()->json($destinations);
    }

    public function store(Request $request, $itineraryId)
    {
        $request->validate([
            'name' => 'required|string',
            'accommodation' => 'required|string',
            'activities' => 'required|array|min:1',
            'activities.*.description' => 'required'
        ]);

        $destination = Destination::create([
            'name' => $request->name,
            'accommodation' => $request->accommodation,
            'itinerary_id' => $itineraryId
        ]);

        foreach ($request->activities as $activity) {

            $destination->activities()->create([
                'description' => $activity['description']
            ]);
        }

        return response()->json([
            'message' => 'Destination created',
            'destination' => $destination->load('activities')
        ], 201);
    }

    public function update(Request $request, $destinationId)
    {
        $destination = Destination::findOrFail($destinationId);

        $destination->update([
            'name' => $request->name,
            'accommodation' => $request->accommodation
        ]);

        return response()->json([
            'message' => 'Destination updated',
            'destination' => $destination
        ]);
    }

    public function destroy($destinationId)
    {
        $destination = Destination::findOrFail($destinationId);

        $destination->delete();

        return response()->json([
            'message' => 'Destination deleted'
        ]);
    }
}
