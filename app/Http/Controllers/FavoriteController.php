<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{

    #[OA\Get(
        path: '/api/favorites',
        summary: 'Display the user\'s favorite itinerary',
        security: [['sanctum' => []]]
    )]
    #[OA\Response(response: 201, description: 'User\'s Favorite itinerary')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]

    public function index()
    {
        $favorites = auth()->user()->favoriteItineraries()->with('destinations')->get();

        return response()->json($favorites);
    }

    #[OA\Post(
        path: '/api/favorites/{itinerary}',
        summary: 'Add an itinerary as a favorite',
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'itinerary',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: 201, description: 'Itinerary Added to favorite successfully')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 402, description: 'Unauthorized')]
    #[OA\Response(response: 403, description: 'Itinerary Not Found')]

    public function store($itineraryId)
    {
        $user = auth()->user();
        $itinerary = Itinerary::findOrFail($itineraryId);

        $user->favoriteItineraries()->syncWithoutDetaching([$itinerary->id]);

        return response()->json([
            'message' => 'Itinerary added to favorites'
        ], 201);
    }

    #[OA\Delete(
        path: '/api/favorites/{itinerary}',
        summary: 'Delete an itinerary from favorites',
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'itinerary',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: 201, description: 'Itinerary Deleted From favorites successfully')]
    #[OA\Response(response: 401, description: 'Unauthenticated')]
    #[OA\Response(response: 402, description: 'Unauthorized')]
    #[OA\Response(response: 403, description: 'Itinerary Not Found')]

    public function destroy($itineraryId)
    {
        $user = auth()->user();
        $user->favoriteItineraries()->detach($itineraryId);

        return response()->json([
            'message' => 'Itinerary removed from favorites'
        ]);
    }
}
