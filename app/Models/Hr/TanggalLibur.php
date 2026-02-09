<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class TanggalLibur extends Model
{
    protected $table      = 'hr_tanggal_libur';
    protected $primaryKey = 'tanggallibur_id';

    protected $fillable = [
        'tahun',
        'tgl_libur',
        'keterangan',
    ];

    protected $casts = [
        'tgl_libur' => 'date',
    ];
}
