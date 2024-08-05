<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Event;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchArticles(Request $request)
    {
        $query = $request->query('query');

        $results = Article::where('title', 'like', "%$query%")
            ->orWhere('content', 'like', "%$query%")
            ->limit(10)
            ->get();

        return response()->json($results);
    }

    public function searchEvents(Request $request)
    {
        $query = $request->query('query');

        $results = Event::where('name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->limit(10)
            ->get();

        return response()->json($results);
    }
}
