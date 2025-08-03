<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $level
 * @property string|null $nik
 * @property string|null $profile_photo
 * @property string $komisariat
 * @property string $jurusan
 * @property string $pt
 * @property string|null $golongan_darah
 * @property string|null $no_whatsapp
 * @property string|null $alamat_malang
 * @property bool $is_verified
 * @property bool $profile_completed
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookLoan> $bookLoans
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Work> $works
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attendances
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfileCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User administrator()
 * @method static \Illuminate\Database\Eloquent\Builder|User pengurus()
 * @method static \Illuminate\Database\Eloquent\Builder|User kader()
 * @method static \Illuminate\Database\Eloquent\Builder|User verified()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const LEVEL_ADMINISTRATOR = 'administrator';
    const LEVEL_PENGURUS = 'pengurus';
    const LEVEL_KADER = 'kader';

    const GOLONGAN_DARAH_OPTIONS = ['A', 'B', 'AB', 'O'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'nik',
        'profile_photo',
        'komisariat',
        'jurusan',
        'pt',
        'golongan_darah',
        'no_whatsapp',
        'alamat_malang',
        'is_verified',
        'profile_completed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_verified' => 'boolean',
        'profile_completed' => 'boolean',
    ];

    /**
     * Scope a query to only include administrators.
     */
    public function scopeAdministrator($query)
    {
        return $query->where('level', self::LEVEL_ADMINISTRATOR);
    }

    /**
     * Scope a query to only include pengurus.
     */
    public function scopePengurus($query)
    {
        return $query->where('level', self::LEVEL_PENGURUS);
    }

    /**
     * Scope a query to only include kader.
     */
    public function scopeKader($query)
    {
        return $query->where('level', self::LEVEL_KADER);
    }

    /**
     * Scope a query to only include verified users.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Check if user is administrator.
     */
    public function isAdministrator(): bool
    {
        return $this->level === self::LEVEL_ADMINISTRATOR;
    }

    /**
     * Check if user is pengurus.
     */
    public function isPengurus(): bool
    {
        return $this->level === self::LEVEL_PENGURUS;
    }

    /**
     * Check if user is kader.
     */
    public function isKader(): bool
    {
        return $this->level === self::LEVEL_KADER;
    }

    /**
     * Check if user can manage other users.
     */
    public function canManageUsers(): bool
    {
        return $this->isAdministrator() || $this->isPengurus();
    }

    /**
     * Get the book loans for the user.
     */
    public function bookLoans(): HasMany
    {
        return $this->hasMany(BookLoan::class);
    }

    /**
     * Get the works created by the user.
     */
    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }

    /**
     * Get the attendances for the user.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}