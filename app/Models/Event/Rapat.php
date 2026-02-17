<?php
namespace App\Models\Event;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rapat extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'event_rapat';
    protected $primaryKey = 'rapat_id';

    protected $fillable = [
        'jenis_rapat',
        'judul_kegiatan',
        'tgl_rapat',
        'waktu_mulai',
        'waktu_selesai',
        'tempat_rapat',
        'ketua_user_id',
        'notulen_user_id',
        'author_user_id',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tgl_rapat'     => 'date',
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Get the agendas for the rapat.
     */
    public function agendas()
    {
        return $this->hasMany(RapatAgenda::class, 'rapat_id');
    }

    /**
     * Get the pesertas for the rapat.
     */
    public function pesertas()
    {
        return $this->hasMany(RapatPeserta::class, 'rapat_id');
    }

    /**
     * Get the entitas for the rapat.
     */
    public function entitas()
    {
        return $this->hasMany(RapatEntitas::class, 'rapat_id');
    }

    /**
     * Get the creator of the rapat.
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function ketua_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'ketua_user_id');
    }

    public function notulen_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'notulen_user_id');
    }

    public function author_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'author_user_id');
    }
}
