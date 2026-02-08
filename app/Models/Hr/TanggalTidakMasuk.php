<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggalTidakMasuk extends Model
{
    use HasFactory;

    protected $table    = 'hr_tanggal_tidak_masuk';
    protected $fillable = ['tanggal', 'tahun', 'keterangan'];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
