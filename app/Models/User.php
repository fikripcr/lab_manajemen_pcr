<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'nim',
        'nip',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        'password'          => 'hashed',
        'id'                => 'string', // Ensure id is treated as string for encryption purposes
    ];

    protected static $logName      = 'user';
    protected static $logFillable  = true;
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Relationship: User has many PC assignments
     */
    public function pcAssignments()
    {
        return $this->hasMany(PcAssignment::class, 'user_id');
    }

    /**
     * Relationship: User has many log entries (PC usage)
     */
    public function logPenggunaanPcs()
    {
        return $this->hasMany(LogPenggunaanPc::class, 'user_id');
    }

    /**
     * Relationship: User has many software requests as dosen
     */
    public function softwareRequests()
    {
        return $this->hasMany(RequestSoftware::class, 'dosen_id');
    }

    /**
     * Relationship: User has many events as penyelenggara
     */
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'penyelenggara_id');
    }

    /**
     * Relationship: User has many damage reports as teknisi
     */
    public function laporanKerusakans()
    {
        return $this->hasMany(LaporanKerusakan::class, 'teknisi_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useFallbackUrl('/fallback/fallbackAvatarImage.png')
            ->useFallbackPath(public_path('fallback/fallbackAvatarImage.png'))
            ->useDisk('public');
    }

    /**
     * Register the media conversions for this model.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 150, 150)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->fit(Fit::Crop, 400, 400)
            ->nonQueued();

        $this->addMediaConversion('avatar_small')
            ->fit(Fit::Crop, 50, 50)
            ->nonQueued();
    }

    /**
     * Get the user's notifications.
     */
    public function notifications()
    {
        return $this->morphMany(\App\Models\Notification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the user's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->morphMany(\App\Models\Notification::class, 'notifiable')
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc');
    }
}
