<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Work
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property int $user_id
 * @property bool $is_available_print
 * @property bool $is_available_digital
 * @property string|null $digital_url
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Work newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Work newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Work query()
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereIsAvailablePrint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereIsAvailableDigital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Work artikel()
 * @method static \Illuminate\Database\Eloquent\Builder|Work esai()
 * @method static \Illuminate\Database\Eloquent\Builder|Work kti()
 * @method static \Database\Factories\WorkFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Work extends Model
{
    use HasFactory;

    const TYPE_ARTIKEL = 'artikel';
    const TYPE_ESAI = 'esai';
    const TYPE_KTI = 'kti';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'type',
        'user_id',
        'is_available_print',
        'is_available_digital',
        'digital_url',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_available_print' => 'boolean',
        'is_available_digital' => 'boolean',
        'user_id' => 'integer',
    ];

    /**
     * Scope a query to only include artikel.
     */
    public function scopeArtikel($query)
    {
        return $query->where('type', self::TYPE_ARTIKEL);
    }

    /**
     * Scope a query to only include esai.
     */
    public function scopeEsai($query)
    {
        return $query->where('type', self::TYPE_ESAI);
    }

    /**
     * Scope a query to only include KTI.
     */
    public function scopeKti($query)
    {
        return $query->where('type', self::TYPE_KTI);
    }

    /**
     * Get the user who created this work.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_ARTIKEL => 'Artikel/Jurnal',
            self::TYPE_ESAI => 'Esai',
            self::TYPE_KTI => 'KTI',
            default => $this->type,
        };
    }
}