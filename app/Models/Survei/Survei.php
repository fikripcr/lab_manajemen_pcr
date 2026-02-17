<?php
namespace App\Models\Survei;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survei extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'survei_survei';

    protected $fillable = [
        'judul',
        'deskripsi',
        'slug',
        'mode',
        'target_role',
        'is_aktif',
        'wajib_login',
        'bisa_isi_ulang',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'is_aktif'        => 'boolean',
        'wajib_login'     => 'boolean',
        'bisa_isi_ulang'  => 'boolean',
        'tanggal_mulai'   => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function halaman()
    {
        return $this->hasMany(Halaman::class, 'survei_id')->orderBy('urutan');
    }

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'survei_id');
    }

    public function pengisian()
    {
        return $this->hasMany(Pengisian::class, 'survei_id');
    }

    public function relasiKonteks()
    {
        return $this->hasMany(RelasiKonteks::class, 'survei_id');
    }
}
