<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Article;
use App\Models\Campaign;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $starredArticles = Article::where('is_starred', true)->get();
        $starredEvents = Event::where('is_starred', true)->get();
        $starredCampaigns = Campaign::where('is_starred', true)->get();

        return view('home', compact('starredArticles', 'starredEvents', 'starredCampaigns'));
    }

    public function about()
    {
        $userCount = User::count();

        return view('about', compact('userCount'));
    }

    public function contact()
    {

        return view('contact');
    }

    public function starArticle(Request $request)
    {
        $article = Article::where('slug', $request->slug)->first();

        if ($article) {
            $article->is_starred = !$article->is_starred;
            $article->save();

            return response()->json(['success' => true, 'is_starred' => $article->is_starred]);
        }

        return response()->json(['success' => false]);
    }

    public function starEvent(Request $request)
    {
        $event = Event::where('slug', $request->slug)->first();

        if ($event) {
            $event->is_starred = !$event->is_starred;
            $event->save();

            return response()->json(['success' => true, 'is_starred' => $event->is_starred]);
        }

        return response()->json(['success' => false]);
    }

    public function starCampaign(Request $request)
    {
        $campaign = Campaign::where('slug', $request->slug)->first();

        if ($campaign) {
            $campaign->is_starred = !$campaign->is_starred;
            $campaign->save();

            return response()->json(['success' => true, 'is_starred' => $campaign->is_starred]);
        }

        return response()->json(['success' => false]);
    }
}
