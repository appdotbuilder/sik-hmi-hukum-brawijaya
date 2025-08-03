<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Book
 *
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string $type
 * @property bool $is_available_print
 * @property bool $is_available_digital
 * @property string|null $digital_url
 * @property string|null $description
 * @property int $loan_duration_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookLoan> $loans
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereIsAvailablePrint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereIsAvailableDigital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book availablePrint()
 * @method static \Illuminate\Database\Eloquent\Builder|Book availableDigital()
 * @method static \Database\Factories\BookFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Book extends Model
{
    use HasFactory;

    const TYPE_BOOK = 'book';
    const TYPE_WORK = 'work';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'author',
        'type',
        'is_available_print',
        'is_available_digital',
        'digital_url',
        'description',
        'loan_duration_days',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_available_print' => 'boolean',
        'is_available_digital' => 'boolean',
        'loan_duration_days' => 'integer',
    ];

    /**
     * Scope a query to only include books available for print loan.
     */
    public function scopeAvailablePrint($query)
    {
        return $query->where('is_available_print', true);
    }

    /**
     * Scope a query to only include books available digitally.
     */
    public function scopeAvailableDigital($query)
    {
        return $query->where('is_available_digital', true);
    }

    /**
     * Get the loans for this book.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(BookLoan::class);
    }

    /**
     * Check if book is currently on loan.
     */
    public function isOnLoan(): bool
    {
        return $this->loans()->where('status', 'borrowed')->exists();
    }

    /**
     * Get available print copies count.
     */
    public function getAvailableCopiesAttribute(): int
    {
        if (!$this->is_available_print) {
            return 0;
        }
        
        $onLoanCount = $this->loans()->where('status', 'borrowed')->count();
        return max(0, 1 - $onLoanCount); // Assuming 1 copy per book for now
    }
}