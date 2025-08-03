<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter by verification status
        if ($request->filled('verified')) {
            $query->where('is_verified', $request->boolean('verified'));
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')
                      ->paginate(15)
                      ->withQueryString();

        return Inertia::render('users/index', [
            'users' => $users,
            'filters' => $request->only(['level', 'verified', 'search']),
            'stats' => [
                'total' => User::count(),
                'verified' => User::where('is_verified', true)->count(),
                'administrators' => User::administrator()->count(),
                'pengurus' => User::pengurus()->count(),
                'kader' => User::kader()->count(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('users/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        return redirect()->route('users.show', $user)
            ->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['bookLoans.book', 'works', 'attendances']);

        return Inertia::render('users/show', [
            'user' => $user,
            'stats' => [
                'total_loans' => $user->bookLoans->count(),
                'active_loans' => $user->bookLoans->where('status', 'borrowed')->count(),
                'total_works' => $user->works->count(),
                'total_attendances' => $user->attendances->count(),
                'present_attendances' => $user->attendances->where('status', 'present')->count(),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('users/edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('users.show', $user)
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deletion of administrators if they're the last one
        if ($user->isAdministrator() && User::administrator()->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus administrator terakhir.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }


}