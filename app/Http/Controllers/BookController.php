<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\BookLoan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Filter by availability
        if ($request->filled('available_print')) {
            $query->where('is_available_print', $request->boolean('available_print'));
        }

        if ($request->filled('available_digital')) {
            $query->where('is_available_digital', $request->boolean('available_digital'));
        }

        // Search by title or author
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $books = $query->withCount(['loans as active_loans_count' => function ($query) {
                       $query->where('status', BookLoan::STATUS_BORROWED);
                   }])
                   ->orderBy('created_at', 'desc')
                   ->paginate(12)
                   ->withQueryString();

        return Inertia::render('books/index', [
            'books' => $books,
            'filters' => $request->only(['available_print', 'available_digital', 'search']),
            'stats' => [
                'total' => Book::count(),
                'print_available' => Book::where('is_available_print', true)->count(),
                'digital_available' => Book::where('is_available_digital', true)->count(),
                'currently_borrowed' => BookLoan::borrowed()->count(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('books/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());

        return redirect()->route('books.show', $book)
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load([
            'loans' => function ($query) {
                $query->with('user')->orderBy('created_at', 'desc');
            }
        ]);

        return Inertia::render('books/show', [
            'book' => $book,
            'canBorrow' => auth()->user()?->isKader() && $book->is_available_print && !$book->isOnLoan(),
            'userLoan' => auth()->user() ? 
                BookLoan::where('user_id', auth()->id())
                        ->where('book_id', $book->id)
                        ->whereIn('status', [BookLoan::STATUS_PENDING, BookLoan::STATUS_BORROWED])
                        ->first() : null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return Inertia::render('books/edit', [
            'book' => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return redirect()->route('books.show', $book)
            ->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Check if book has active loans
        if ($book->loans()->whereIn('status', [BookLoan::STATUS_PENDING, BookLoan::STATUS_BORROWED])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus buku yang sedang dipinjam atau menunggu verifikasi.');
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}