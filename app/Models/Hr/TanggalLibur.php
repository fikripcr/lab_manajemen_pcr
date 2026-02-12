<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class TanggalLibur extends Model
{
    use SoftDeletes, Blameable, HashidBinding;
    protected $table      = 'hr_tanggal_libur';
    protected $primaryKey = 'tanggallibur_id';

    protected $fillable = [
        'tahun',
        'tgl_libur',
        'keterangan',        'created_by',        'updated_by',        'deleted_by',
    
    
    
    ];

    protected $casts = [
        'tgl_libur' => 'date',
    ];
}
