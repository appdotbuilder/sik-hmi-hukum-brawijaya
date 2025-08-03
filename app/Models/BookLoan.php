<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\BookLoan
 *
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $borrowed_at
 * @property \Illuminate\Support\Carbon $due_date
 * @property \Illuminate\Support\Carbon|null $returned_at
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Book $book
 * @property-read \App\Models\User|null $verifier
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan whereBorrowedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan whereReturnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan pending()
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan borrowed()
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan returned()
 * @method static \Illuminate\Database\Eloquent\Builder|BookLoan overdue()
 * @method static \Database\Factories\BookLoanFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class BookLoan extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_BORROWED = 'borrowed';
    const STATUS_RETURNED = 'returned';
    const STATUS_REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'borrowed_at',
        'due_date',
        'returned_at',
        'verified_by',
        'verified_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
        'verified_at' => 'datetime',
        'user_id' => 'integer',
        'book_id' => 'integer',
        'verified_by' => 'integer',
    ];

    /**
     * Scope a query to only include pending loans.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include borrowed loans.
     */
    public function scopeBorrowed($query)
    {
        return $query->where('status', self::STATUS_BORROWED);
    }

    /**
     * Scope a query to only include returned loans.
     */
    public function scopeReturned($query)
    {
        return $query->where('status', self::STATUS_RETURNED);
    }

    /**
     * Scope a query to only include overdue loans.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_BORROWED)
                    ->where('due_date', '<', now());
    }

    /**
     * Get the user who borrowed the book.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the borrowed book.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the user who verified the loan.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if the loan is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_BORROWED && $this->due_date < now();
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu Verifikasi',
            self::STATUS_BORROWED => 'Dipinjam',
            self::STATUS_RETURNED => 'Dikembalikan',
            self::STATUS_REJECTED => 'Ditolak',
            default => $this->status,
        };
    }
}