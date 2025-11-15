<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PcAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pc_assignments';
    protected $primaryKey = 'pc_assignments_id';

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'lab_id',
        'nomor_pc',
        'nomor_loker',
        'assigned_date',
        'is_active',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'is_active' => 'boolean',
        'nomor_pc' => 'integer',
        'nomor_loker' => 'integer',
    ];

    /**
     * Relationship: PC Assignment belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: PC Assignment belongs to a schedule
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalKuliah::class, 'jadwal_id');
    }

    /**
     * Relationship: PC Assignment belongs to a lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }

    /**
     * Relationship: PC Assignment has many PC usage logs
     */
    public function logPenggunaanPcs()
    {
        return $this->hasMany(LogPenggunaanPc::class, 'pc_assignment_id');
    }
}
