<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rapat extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pemutu_rapat';
    protected $primaryKey = 'rapat_id';

    protected $fillable = [
        'judul_rapat',
        'tanggal_rapat',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'jenis_rapat',
        'deskripsi',
        'status',
        'notulen',
        'file_notulen',
        'pimpinan_rapat',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_rapat' => 'date',
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
}
