<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;

class DestinationController extends Controller
{

    #[OA\Get(path: '/api/itineraries/{itinerary}/destinations', summary: 'Display All itinerary\'s destinations')]
    #[OA\Parameter(name: 'itinerary', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 201, description: 'Display All itinerary\'s destinations')]

    public function index($itineraryId)
    {
        $destinations = Destination::where('itinerary_id', $itineraryId)
            ->with('activities')
            ->get();

        return response()->json($destinations);
    }

    #[OA\Post(
        path: '/api/itineraries/{itinerary}/destinations',
        summary: 'Create an itinerary\'s Destination',
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'itinerary',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'accommodation', 'activities'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'accommodation', type: 'string'),
                new OA\Property(
                    property: 'activities',
                    type: 'array',
                    minItems: 1,
                    items: new OA\Items(
                        type: 'object',
                        required: ['description'],
                        properties: [
                            new OA\Property(property: 'description', type: 'string'),
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Creating an itinerary\'s Destination')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]

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

    #[OA\Put(
        path: '/api/destinations/{destination}',
        summary: 'Update an itinerary\'s Destination',
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'destination',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'accommodation', 'activities'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'accommodation', type: 'string'),
                new OA\Property(
                    property: 'activities',
                    type: 'array',
                    minItems: 1,
                    items: new OA\Items(
                        type: 'object',
                        required: ['description'],
                        properties: [
                            new OA\Property(property: 'description', type: 'string'),
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(response: 200, description: 'Destination updated successfully')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 403, description: 'Unauthorized')]
    #[OA\Response(response: 404, description: 'Destination not found')]

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

    #[OA\Delete(
        path: '/api/destinations/{destination}',
        summary: 'Delete an itinerary\'s Destination',
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'destination',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: 200, description: 'Destination Deleted successfully')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 403, description: 'Unauthorized')]
    #[OA\Response(response: 404, description: 'Destination not found')]

    public function destroy($destinationId)
    {
        $destination = Destination::findOrFail($destinationId);

        $destination->delete();

        return response()->json([
            'message' => 'Destination deleted'
        ]);
    }
}
