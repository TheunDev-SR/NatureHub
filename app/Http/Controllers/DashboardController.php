<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Article;
use App\Models\Campaign;
use App\Models\Messages;
use Illuminate\Http\Request;
use Icehouseventures\Chartjs\Charts\ChartJs;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = date('n');
        $userCounts = User::whereMonth('created_at', $currentMonth)->count();
        $articleCounts = Article::whereMonth('created_at', $currentMonth)
            ->whereNotNull('approved_at')
            ->count();
        $eventCounts = Event::whereMonth('created_at', $currentMonth)->count();
        $campaignCounts = Campaign::whereMonth('created_at', $currentMonth)->count();

        $monthName = date('F');

        $chartPie = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->labels(['User Counts', 'Article Counts', 'Event Counts', 'Campaign Counts'])
            ->datasets([
                [
                    "label" => "User Counts",
                    'backgroundColor' => ['#3abaf4', '#ffa426', '#66bb6a', '#fc544b'],
                    'data' => [$userCounts, $articleCounts, $eventCounts, $campaignCounts]
                ]
            ])
            ->options([
                "plugins" => [
                    "title" => [
                        "display" => true,
                        "text" => "Data Counts for $monthName",
                        "align" => "center",
                    ],
                ],
            ]);

        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        $totalCounts = [];

        foreach ($months as $month) {
            $userCount = User::whereMonth('created_at', date('m', strtotime($month)))->count();
            $articleCount = Article::whereMonth('created_at', date('m', strtotime($month)))
                ->whereNotNull('approved_at')->count();
            $eventCount = Event::whereMonth('created_at', date('m', strtotime($month)))->count();
            $campaignCount = Campaign::whereMonth('created_at', date('m', strtotime($month)))->count();

            $totalCounts[] = $userCount + $articleCount + $eventCount + $campaignCount;
        }

        $chartBar = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($months)
            ->datasets([
                [
                    "label" => "Total Counts",
                    'backgroundColor' => '#6777EF',
                    'data' => $totalCounts
                ]
            ])
            ->options([
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                    ],
                    "title" => [
                        "display" => true,
                        "text" => "Total Data Counts for Each Month",
                        "align" => "center",
                    ],
                ],
                "scales" => [
                    "y" => [
                        "beginAtZero" => true
                    ]
                ]
            ]);

        return view('admin.dashboard', compact('chartPie', 'chartBar'));
    }

    public function articles(Request $request)
    {
        $query = Article::query()->whereNotNull('approved_at');

        $sortField = 'is_starred';
        $sortDirection = 'desc';

        $requestSortField = $request->input('sort_by', null);
        $requestSortDirection = $request->input('sort_dir', 'asc');

        if ($requestSortField) {
            $sortField = $requestSortField;
            $sortDirection = $requestSortDirection;
        }

        if ($sortField == 'likes') {
            $query->withCount('likes')
                ->orderBy('likes_count', $sortDirection)
                ->orderBy('created_at', 'asc');
        } else {
            try {
                $query->orderBy($sortField, $sortDirection);
            } catch (\Exception $e) {
                $query->orderBy('id', 'asc');
            }
        }

        $articles = $query->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }


    public function events(Request $request)
    {
        $query = Event::query();

        $sortField = $request->input('sort_by', 'start_time');
        $sortDirection = $request->input('sort_dir', 'asc');

        if (!$request->has('sort_by')) {
            $sortField = 'is_starred';
            $sortDirection = 'desc';
        }

        try {
            $query->orderBy($sortField, $sortDirection);
        } catch (\Exception $e) {
            $query->orderBy('start_time', 'asc');
        }

        $events = $query->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    public function campaigns(Request $request)
    {
        $query = Campaign::query();

        $sortField = 'is_starred';
        $sortDirection = 'desc';

        $requestSortField = $request->input('sort_by', null);
        $requestSortDirection = $request->input('sort_dir', 'asc');

        if ($requestSortField) {
            $sortField = $requestSortField;
            $sortDirection = $requestSortDirection;
        }

        if ($sortField == 'likes') {
            $query->withCount('likes')
                ->orderBy('likes_count', $sortDirection)
                ->orderBy('created_at', 'asc');
        } else {
            try {
                $query->orderBy($sortField, $sortDirection);
            } catch (\Exception $e) {
                $query->orderBy('id', 'asc');
            }
        }

        $campaigns = $query->paginate(10);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function pendingArticles(Request $request)
    {
        $query = Article::query()->whereNull('approved_at');

        $sortField = $request->input('sort_by', 'updated_at');
        $sortDirection = $request->input('sort_dir', 'desc');

        try {
            $query->orderBy($sortField, $sortDirection);
        } catch (\Exception $e) {
            $query->orderBy('updated_at', 'desc');
        }

        $articles = $query->paginate(10);

        return view('admin.articles.pending', compact('articles'));
    }

    public function approveArticle(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $article->approved_at = now();
        $article->save();

        Messages::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $article->user_id,
            'article_id' => $article->id,
            'content' => 'Your article "' . $article->title . '" has been approved. Congratulations!',
        ]);

        return redirect()->route('admin.articles')->with('success', 'Article approved successfully');
    }

    public function rejectArticle(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        $article->update(['approved_at' => null]);

        Messages::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $article->user_id,
            'article_id' => $article->id,
            'content' => $request->input('reason'),
            'read' => false,
        ]);

        return redirect()->route('admin.articles.pending')->with('status', 'Article rejected and notification sent.');
    }
}
