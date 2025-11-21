<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Image\Enums\Fit;
use App\Models\Notification;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Searchable\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Searchable\SearchResult;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia, Searchable
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
        'avatar',
        'email_verified_at',
        'expired_at',
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
        'expired_at'        => 'datetime',
        'password'          => 'hashed',
        'id'                => 'string', // Ensure id is treated as string for encryption purposes
    ];

    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

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
            ->useFallbackUrl('/img/no-avatar.png')
            ->useFallbackPath(public_path('img/no-avatar.png'))
            ->useDisk('public')
            ->singleFile();
    }

    /**
     * Register the media conversions for this model.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Fit::Crop, 50, 50)
            ->optimize()
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->fit(Fit::Crop, 200, 200)
            ->optimize()
            ->nonQueued();

        $this->addMediaConversion('large')
            ->fit(Fit::Crop, 512, 512)
            ->optimize()
            ->nonQueued();
    }

    /**
     * Get the user's notifications.
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the user's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc');
    }

    public function labTeam()
    {
        return $this->belongsToMany(Lab::class, 'lab_teams', 'user_id', 'lab_id');
    }

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('users.show', $this->encrypted_id ?? $this->id);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }

    /**
     * Accessor to get the user's avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        return $this->getFirstMediaUrl('avatar', 'large');
    }

    /**
     * Get medium avatar URL
     */
    public function getAvatarMediumUrlAttribute()
    {
        return $this->getFirstMediaUrl('avatar', 'medium');
    }

    /**
     * Get small avatar URL
     */
    public function getAvatarSmallUrlAttribute()
    {
        return $this->getFirstMediaUrl('avatar', 'small');
    }


}
