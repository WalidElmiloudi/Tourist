<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index($destinationId)
    {
        $activities = Activity::where('destination_id', $destinationId)->get();

        return response()->json($activities);
    }

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

    public function destroy($activityId)
    {
        $activity = Activity::findOrFail($activityId);

        $activity->delete();

        return response()->json([
            'message' => 'Activity deleted'
        ]);
    }
}
