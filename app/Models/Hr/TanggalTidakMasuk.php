<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class TanggalTidakMasuk extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table    = 'hr_tanggal_tidak_masuk';
    protected $fillable = ['tanggal', 'tahun', 'keterangan'        'created_by',        'updated_by',        'deleted_by',
    
    
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
