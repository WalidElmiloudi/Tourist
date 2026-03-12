<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favoriteItineraries()->with('destinations')->get();

        return response()->json($favorites);
    }

    public function store($itineraryId)
    {
        $user = auth()->user();
        $itinerary = Itinerary::findOrFail($itineraryId);

        $user->favoriteItineraries()->syncWithoutDetaching([$itinerary->id]);

        return response()->json([
            'message' => 'Itinerary added to favorites'
        ], 201);
    }

    public function destroy($itineraryId)
    {
        $user = auth()->user();
        $user->favoriteItineraries()->detach($itineraryId);

        return response()->json([
            'message' => 'Itinerary removed from favorites'
        ]);
    }
}
