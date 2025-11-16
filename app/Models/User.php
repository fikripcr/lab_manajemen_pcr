<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasMedia;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasMedia, SoftDeletes, LogsActivity;

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
        'password' => 'hashed',
        'id' =>'string' // Ensure id is treated as string for encryption purposes
    ];

    protected static $logName = 'user';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Remove the custom auth methods as they're not needed for standard Laravel auth

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

    /**
     * Get the value of the model's route key.
     */
    public function getRouteKey()
    {
        return encryptId($this->getKey());
    }

    /**
     * Get the avatar URL attribute
     */
    public function getAvatarUrlAttribute()
    {
        // Try to get avatar from HasMedia trait first
        $media = $this->getFirstMediaByCollection('avatar');
        if ($media) {
            // If thumbnail exists, use it; otherwise, use the original image
            if (isset($media->thumbnail_url)) {
                return $media->thumbnail_url;
            } else {
                return asset('storage/' . $media->file_path);
            }
        }

        // Fallback: check if legacy avatar field exists (for backward compatibility)
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Ultimate fallback: UI Avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
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
