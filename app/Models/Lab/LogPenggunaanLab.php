<?php
namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogPenggunaanLab extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'lab_log_penggunaan_labs';
    protected $primaryKey = 'log_penggunaan_labs_id';

    protected $fillable = [
        'kegiatan_id',
        'lab_id',
        'nama_peserta',
        'email_peserta',
        'npm_peserta',
        'nomor_pc',
        'kondisi',
        'catatan_umum',
        'waktu_isi',
    ];

    protected $casts = [
        'waktu_isi' => 'datetime',
        'nomor_pc'  => 'integer',
    ];

    /**
     * Relationship: Log belongs to an event
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    /**
     * Relationship: Log belongs to a lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }

    /**
     * Accessor to get encrypted log_penggunaan_labs_id
     */
    public function getEncryptedLogPenggunaanLabsIdAttribute()
    {
        return encryptId($this->log_penggunaan_labs_id);
    }

    /**
     * Accessor to get encrypted lab_id
     */
    public function getEncryptedLabIdAttribute()
    {
        return encryptId($this->lab_id);
    }

    /**
     * Accessor to get encrypted kegiatan_id
     */
    public function getEncryptedKegiatanIdAttribute()
    {
        return encryptId($this->kegiatan_id);
    }
}
