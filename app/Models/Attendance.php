<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Attendance
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $bidang
 * @property string|null $program_kegiatan
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $checked_in_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\User|null $creator
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereBidang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereProgramKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCheckedInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance bidang()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance programKegiatan()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance present()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance absent()
 * @method static \Database\Factories\AttendanceFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Attendance extends Model
{
    use HasFactory;

    const TYPE_BIDANG = 'bidang';
    const TYPE_PROGRAM_KEGIATAN = 'program_kegiatan';

    const BIDANG_PEMBINAAN_ANGGOTA = 'pembinaan_anggota';
    const BIDANG_LITBANG = 'litbang';
    const BIDANG_P2K = 'p2k';
    const BIDANG_PP = 'pp';
    const BIDANG_PTKP = 'ptkp';
    const BIDANG_KPP = 'kpp';

    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'bidang',
        'program_kegiatan',
        'title',
        'description',
        'date',
        'status',
        'checked_in_at',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
        'checked_in_at' => 'datetime',
        'user_id' => 'integer',
        'created_by' => 'integer',
    ];

    /**
     * Scope a query to only include bidang attendance.
     */
    public function scopeBidang($query)
    {
        return $query->where('type', self::TYPE_BIDANG);
    }

    /**
     * Scope a query to only include program kegiatan attendance.
     */
    public function scopeProgramKegiatan($query)
    {
        return $query->where('type', self::TYPE_PROGRAM_KEGIATAN);
    }

    /**
     * Scope a query to only include present attendance.
     */
    public function scopePresent($query)
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    /**
     * Scope a query to only include absent attendance.
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', self::STATUS_ABSENT);
    }

    /**
     * Get the user for this attendance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who created this attendance record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the bidang label.
     */
    public function getBidangLabelAttribute(): string
    {
        return match($this->bidang) {
            self::BIDANG_PEMBINAAN_ANGGOTA => 'Pembinaan Anggota',
            self::BIDANG_LITBANG => 'Litbang',
            self::BIDANG_P2K => 'P2K',
            self::BIDANG_PP => 'PP',
            self::BIDANG_PTKP => 'PTKP',
            self::BIDANG_KPP => 'KPP',
            default => $this->bidang,
        };
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PRESENT => 'Hadir',
            self::STATUS_ABSENT => 'Tidak Hadir',
            self::STATUS_LATE => 'Terlambat',
            default => $this->status,
        };
    }

    /**
     * Get the type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_BIDANG => 'Bidang',
            self::TYPE_PROGRAM_KEGIATAN => 'Program Kegiatan',
            default => $this->type,
        };
    }
}