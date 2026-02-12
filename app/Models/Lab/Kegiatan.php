<?php
namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'lab_kegiatans';
    protected $primaryKey = 'kegiatan_id';

    protected $fillable = [
        'lab_id',
        'penyelenggara_id',
        'nama_kegiatan',
        'deskripsi',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'catatan_pic',
        'qr_code_path',
        'berita_acara_path',        'created_by',        'updated_by',        'deleted_by',
    
    
    
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'jam_mulai'   => 'datetime',
        'jam_selesai' => 'datetime',
        'status'      => 'string',
    ];

    /**
     * Relationship: Event belongs to a lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }

    /**
     * Relationship: Event belongs to a penyelenggara (user)
     */
    public function penyelenggara()
    {
        return $this->belongsTo(User::class, 'penyelenggara_id');
    }

    /**
     * Relationship: Event has many lab usage logs
     */
    public function logPenggunaanLabs()
    {
        return $this->hasMany(LogPenggunaanLab::class, 'kegiatan_id');
    }

    /**
     * Accessor to get encrypted kegiatan_id
     */
    public function getEncryptedKegiatanIdAttribute()
    {
        return encryptId($this->kegiatan_id);
    }

    /**
     * Accessor to get encrypted lab_id
     */
    public function getEncryptedLabIdAttribute()
    {
        return encryptId($this->lab_id);
    }

    /**
     * Accessor to get encrypted penyelenggara_id
     */
    public function getEncryptedPenyelenggaraIdAttribute()
    {
        return encryptId($this->penyelenggara_id);
    }
}
