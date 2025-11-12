<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPenggunaanLab extends Model
{
    use HasFactory;

    protected $table = 'log_penggunaan_labs';

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
        'nomor_pc' => 'integer',
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
}