<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Book;
use App\Models\User;
use App\Models\Work;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page with app overview.
     */
    public function index()
    {
        $stats = [
            'total_kader' => User::kader()->verified()->count(),
            'total_books' => Book::count(),
            'total_works' => Work::count(),
            'recent_activities' => Attendance::with('user')
                ->orderBy('date', 'desc')
                ->limit(3)
                ->get(),
        ];

        // Get featured books and works
        $featuredBooks = Book::availableDigital()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $featuredWorks = Work::with('user')
            ->where('is_available_digital', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return Inertia::render('welcome', [
            'stats' => $stats,
            'featuredBooks' => $featuredBooks,
            'featuredWorks' => $featuredWorks,
        ]);
    }
}