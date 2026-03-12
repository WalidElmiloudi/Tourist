<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;

class ActivityController extends Controller
{

    #[OA\Get(
        path: '/api/destinations/{destination}/activities',
        summary: 'Displaying the activities under a destination'
    )]
    #[OA\Parameter(
        name: 'destination',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 201,
        description: 'dispalying all the activities of destination succesfully'
    )]

    public function index($destinationId)
    {
        $activities = Activity::where('destination_id', $destinationId)->get();

        return response()->json($activities);
    }

    #[OA\Post(
        path: '/api/destinations/{destination}/activities',
        summary: 'Create an activity for a destination',
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
            required: ['description'],
            properties: [
                new OA\Property(property: 'description', type: 'string')
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Activity Created Succesfully')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]

    public function store(Request $request, $destinationId)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        $activity = Activity::create([
            'description' => $request->description,
            'destination_id' => $destinationId
        ]);

        return response()->json([
            'message' => 'Activity created',
            'activity' => $activity
        ], 201);
    }

    #[OA\Put(
        path: '/api/activities/{activity}',
        summary: 'Update an activity',
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'activity',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['description'],
            properties: [
                new OA\Property(property: 'description', type: 'string')
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Activity Updated Succesfully')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 402, description: 'Unauthorized')]
    #[OA\Response(response: 403, description: 'Activity Not Found')]

    public function update(Request $request, $activityId)
    {
        $activity = Activity::findOrFail($activityId);

        $activity->update([
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Activity updated',
            'activity' => $activity
        ]);
    }

    #[OA\Delete(
        path: '/api/activities/{activity}',
        summary: 'Delete an activity',
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'activity',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: 201, description: 'Activity Deleted Succesfully')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 402, description: 'Unauthorized')]
    #[OA\Response(response: 403, description: 'Activity Not Found')]

    public function destroy($activityId)
    {
        $activity = Activity::findOrFail($activityId);

        $activity->delete();

        return response()->json([
            'message' => 'Activity deleted'
        ]);
    }
}
