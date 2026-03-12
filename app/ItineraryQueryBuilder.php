<?php
namespace App\Services;

use App\Models\Itinerary;
use App\Models\User;

class ItineraryQueryBuilder
{
    //Get all itineraries with destinations
    public static function allWithDestinations()
    {
        return Itinerary::with('destinations.activities')->get();
    }

    //Filter by category and duration
    public static function filterByCategoryDuration($category = null, $duration = null)
    {
        $query = Itinerary::query();

        if($category){
            $query->where('category', $category);
        }

        if($duration){
            $query->where('duration', '<=', $duration);
        }

        return $query->with('destinations')->get();
    }

    //Search itineraries by keyword in title
    public static function searchByTitle($keyword)
    {
        return Itinerary::where('title', 'ilike', "%{$keyword}%")
                        ->with('destinations')
                        ->get();
    }

    //Get most popular itineraries (by number of favorites)
    public static function mostPopular($limit = 10)
    {
        return Itinerary::withCount('favoritedBy')
                        ->orderByDesc('favorited_by_count')
                        ->with('destinations')
                        ->take($limit)
                        ->get();
    }

    //Stats: total itineraries per category
    public static function totalByCategory()
    {
        return Itinerary::selectRaw('category, count(*) as total')
                        ->groupBy('category')
                        ->get();
    }

    //Stats: total users registered per month
    public static function usersPerMonth()
    {
        return User::selectRaw("to_char(created_at, 'YYYY-MM') as month, count(*) as total")
                   ->groupBy('month')
                   ->orderBy('month')
                   ->get();
    }
}