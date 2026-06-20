<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats
        $totalForms = $user->forms()->count();
        $totalSubmissions = Submission::whereIn('form_id', $user->forms()->pluck('id'))->count();
        $totalViews = 0; // implement tracking if needed

        // Completion rate (dummy logic)
        $completionRate = $totalForms > 0 ? round(($totalSubmissions / ($totalForms * 10)) * 100, 1) : 0;

        // Recent forms (latest 5)
        $recentForms = $user->forms()
            ->withCount('submissions')
            ->latest()
            ->limit(5)
            ->get();

        // Chart data: submissions per day (last 7 days)
        $submissionsPerDay = Submission::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->whereIn('form_id', $user->forms()->pluck('id'))
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        // Fill missing dates with 0
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dates[$date] = $submissionsPerDay[$date] ?? 0;
        }

        $chartLabels = $dates->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->format('D'))->toArray();
        $chartData = $dates->values()->toArray();

        // Form popularity data (for doughnut chart)
        $formStats = $user->forms()->withCount('submissions')->get();
        $formLabels = $formStats->pluck('title')->map(fn($t) => Str::limit($t, 20))->toArray();
        $formData = $formStats->pluck('submissions_count')->toArray();

        return view('dashboard.index', compact(
            'totalForms',
            'totalSubmissions',
            'totalViews',
            'completionRate',
            'recentForms',
            'chartLabels',
            'chartData',
            'formLabels',
            'formData'
        ));
    }

    public function analytics()
    {
        $user = Auth::user();
        $totalForms = $user->forms()->count();
        $totalSubmissions = Submission::whereIn('form_id', $user->forms()->pluck('id'))->count();

        // Last 30 days submissions trend
        $submissionsPerDay = Submission::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->whereIn('form_id', $user->forms()->pluck('id'))
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dates[$date] = $submissionsPerDay[$date] ?? 0;
        }

        $chartLabels = $dates->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))->toArray();
        $chartData = $dates->values()->toArray();

        // Form popularity (submissions per form)
        $formStats = $user->forms()->withCount('submissions')->get();
        $formLabels = $formStats->pluck('title')->map(fn($t) => Str::limit($t, 20))->toArray();
        $formData = $formStats->pluck('submissions_count')->toArray();

        // Completion rate
        $completionRate = $totalForms > 0 ? round(($totalSubmissions / ($totalForms * 10)) * 100, 1) : 0;

        return view('analytics', compact(
            'totalForms',
            'totalSubmissions',
            'completionRate',
            'chartLabels',
            'chartData',
            'formLabels',
            'formData'
        ));
    }
}