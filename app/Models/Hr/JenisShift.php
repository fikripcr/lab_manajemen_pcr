<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisShift extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_jenis_shift';
    protected $primaryKey = 'jenis_shift_id';

    protected $fillable = [
        'jenis_shift',
        'jam_masuk',
        'jam_masuk_awal',
        'jam_masuk_akhir',
        'jam_pulang',
        'jam_pulang_awal',
        'jam_pulang_akhir',
        'is_active',
        'created_by',
        'updated_by',
    ];

}
