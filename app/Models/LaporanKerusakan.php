<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanKerusakan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporan_kerusakan';
    protected $primaryKey = 'laporan_kerusakan_id';

    protected $fillable = [
        'inventaris_id',
        'teknisi_id',
        'deskripsi_kerusakan',
        'status',
        'catatan_perbaikan',
        'foto_sebelum',
        'foto_sesudah',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Relationship: Damage report belongs to an inventory
     */
    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }

    /**
     * Relationship: Damage report belongs to a technician (user)
     */
    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
}
