<?php
namespace App\Models\Event;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'events';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'judul_event',
        'jenis_event',
        'judul_Kegiatan',
        'jenis_Kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'deskripsi',
        'buku_tamu_token',
        'pic_user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'encrypted_event_id',
        'judul_Kegiatan',
        'jenis_Kegiatan',
    ];

    public function getRouteKeyName()
    {
        return 'event_id';
    }

    public function getEncryptedEventIdAttribute()
    {
        return encryptId($this->event_id);
    }

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pic()
    {
        return $this->belongsTo(\App\Models\User::class, 'pic_user_id');
    }

    public function tamus()
    {
        return $this->hasMany(EventTamu::class, 'event_id');
    }

    public function teams()
    {
        return $this->hasMany(EventTeam::class, 'event_id');
    }

    public function getJudulKegiatanAttribute()
    {
        return $this->judul_event;
    }

    public function setJudulKegiatanAttribute($value)
    {
        $this->attributes['judul_event'] = $value;
    }

    public function getJenisKegiatanAttribute()
    {
        return $this->jenis_event;
    }

    public function setJenisKegiatanAttribute($value)
    {
        $this->attributes['jenis_event'] = $value;
    }
}
