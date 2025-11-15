<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogPenggunaanPc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'log_penggunaan_pcs';
    protected $primaryKey = 'log_penggunaan_pcs_id';

    protected $fillable = [
        'pc_assignment_id',
        'user_id',
        'jadwal_id',
        'lab_id',
        'status_pc',
        'kondisi',
        'catatan_umum',
        'waktu_isi',
    ];

    protected $casts = [
        'waktu_isi' => 'datetime',
    ];

    /**
     * Relationship: Log belongs to a PC assignment
     */
    public function pcAssignment()
    {
        return $this->belongsTo(PcAssignment::class, 'pc_assignment_id');
    }

    /**
     * Relationship: Log belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Log belongs to a schedule
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalKuliah::class, 'jadwal_id');
    }

    /**
     * Relationship: Log belongs to a lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }
}
