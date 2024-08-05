<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Article;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function generatePdfReport()
    {
        try {
            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $userCounts = [];
            $articleCounts = [];
            $eventCounts = [];
            $campaignCounts = [];
            $totalCounts = [];
            $mostLikedArticlesPerMonth = [];
            $mostLikedCampaignsPerMonth = [];

            foreach ($months as $month) {
                $monthNumber = date('m', strtotime($month));

                $userCounts[$month] = User::whereMonth('created_at', $monthNumber)->count();
                $articleCounts[$month] = Article::whereMonth('created_at', $monthNumber)->whereNotNull('approved_at')->count();
                $eventCounts[$month] = Event::whereMonth('created_at', $monthNumber)->count();
                $campaignCounts[$month] = Campaign::whereMonth('created_at', $monthNumber)->count();

                $totalCounts[$month] = $userCounts[$month] + $articleCounts[$month] + $eventCounts[$month] + $campaignCounts[$month];

                $mostLikedArticlesPerMonth[$month] = Article::whereMonth('created_at', $monthNumber)
                    ->withCount('likes')
                    ->orderBy('likes_count', 'desc')
                    ->take(10)
                    ->get();

                $mostLikedCampaignsPerMonth[$month] = Campaign::whereMonth('created_at', $monthNumber)
                    ->withCount('likes')
                    ->orderBy('likes_count', 'desc')
                    ->take(10)
                    ->get();
            }

            $data = [
                'months' => $months,
                'userCounts' => $userCounts,
                'articleCounts' => $articleCounts,
                'eventCounts' => $eventCounts,
                'campaignCounts' => $campaignCounts,
                'totalCounts' => $totalCounts,
                'mostLikedArticlesPerMonth' => $mostLikedArticlesPerMonth,
                'mostLikedCampaignsPerMonth' => $mostLikedCampaignsPerMonth,
            ];

            $pdf = PDF::loadView('admin.summary', $data);
            return $pdf->download('summary_report.pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
