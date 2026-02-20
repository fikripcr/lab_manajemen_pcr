<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TanggalTidakHadir extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_tanggal_tidak_hadir';
    protected $primaryKey = 'tanggaltidakhadir_id';

    protected $appends = ['encrypted_tanggaltidakhadir_id'];

    public function getEncryptedTanggaltidakhadirIdAttribute()
    {
        return encryptId($this->tanggaltidakhadir_id);
    }

    public function getRouteKeyName()
    {
        return 'tanggaltidakhadir_id';
    }

    protected $fillable = [
        'jenis_ketidakhadiran',
        'tgl',
        'keterangan',
        'additional_info',
        'is_full_day',
        'waktu_mulai',
        'waktu_selesai',
        'model',
        'model_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tgl'             => 'date',
        'additional_info' => 'json',
        'is_full_day'     => 'boolean',
    ];
}
