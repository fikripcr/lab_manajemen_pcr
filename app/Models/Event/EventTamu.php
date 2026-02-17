<?php
namespace App\Models\Event;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EventTamu extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding, InteractsWithMedia;

    protected $table      = 'event_tamus';
    protected $primaryKey = 'eventtamu_id';

    protected $fillable = [
        'event_id',
        'nama_tamu',
        'instansi',
        'jabatan',
        'kontak',
        'tujuan',
        'waktu_datang',
        'foto_url',
        'ttd_url',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'waktu_datang' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('guest_photo')
            ->singleFile();

        $this->addMediaCollection('guest_signature')
            ->singleFile();
    }

    /**
     * Accessor for Photo URL
     */
    public function getPhotoUrlAttribute()
    {
        return $this->getFirstMediaUrl('guest_photo') ?: null;
    }

    /**
     * Accessor for Signature URL
     */
    public function getSignatureUrlAttribute()
    {
        return $this->getFirstMediaUrl('guest_signature') ?: null;
    }
}
