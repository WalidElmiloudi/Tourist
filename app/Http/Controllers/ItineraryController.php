<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;
use App\Models\Itinerary;

class ItineraryController extends Controller
{


    #[OA\Get(path: '/api/itineraries', summary: 'Display All itineraries')]
    #[OA\Response(response: 201, description: 'Displaying All the itineraries')]

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

    #[OA\Get(path: '/api/itineraries/{id}', summary: 'Show an itinerary')]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 201, description: 'Showing the itinerary')]

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

    public function update(Request $request, $id)
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

        $itinerary = Itinerary::findOrFail($id);

        if ($itinerary->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        DB::transaction(function () use ($request, $itinerary) {

            $itinerary->update([
                'title' => $request->title,
                'category' => $request->category,
                'duration' => $request->duration,
                'image' => $request->image
            ]);

            $itinerary->destinations()->delete();

            foreach ($request->destinations as $destinationData) {

                $destination = $itinerary->destinations()->create([
                    'name' => $destinationData['name'],
                    'accommodation' => $destinationData['accommodation']
                ]);

                foreach ($destinationData['activities'] as $activityData) {

                    $destination->activities()->create([
                        'description' => $activityData['description']
                    ]);
                }
            }
        });

        return response()->json([
            'message' => 'Itinerary updated successfully',
            'data' => $itinerary->load('destinations.activities')
        ]);
    }

    public function destroy($id)
    {
        $itinerary = Itinerary::findOrFail($id);

        if ($itinerary->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $itinerary->delete();

        return response()->json([
            'message' => 'Itinerary deleted'
        ]);
    }
}
