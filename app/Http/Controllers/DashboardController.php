<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\User;
use App\Models\Work;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with key statistics.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::verified()->count(),
            'total_kader' => User::kader()->count(),
            'total_pengurus' => User::pengurus()->count(),
            'total_administrator' => User::administrator()->count(),
            'total_books' => Book::count(),
            'total_works' => Work::count(),
            'active_loans' => BookLoan::borrowed()->count(),
            'pending_loans' => BookLoan::pending()->count(),
            'overdue_loans' => BookLoan::overdue()->count(),
            'recent_attendances' => Attendance::with('user')
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get(),
        ];

        // Get recent activities
        $recentLoans = BookLoan::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentWorks = Work::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('dashboard', [
            'stats' => $stats,
            'recentLoans' => $recentLoans,
            'recentWorks' => $recentWorks,
        ]);
    }
}