<?php

namespace App\Models\Survei;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengisian extends Model
{
    use HasFactory;

    protected $table = 'survei_pengisian';

    protected $primaryKey = 'pengisian_id';

    protected $fillable = [
        'survei_id',
        'user_id',
        'entitas_target_type',
        'entitas_target_id',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'ip_address',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function survei()
    {
        return $this->belongsTo(Survei::class, 'survei_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the target entity (JadwalKuliah, Dosen, etc).
     */
    public function entitasTarget()
    {
        return $this->morphTo();
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'pengisian_id');
    }
}
