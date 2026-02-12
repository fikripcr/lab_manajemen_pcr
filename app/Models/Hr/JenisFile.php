<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisFile extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_jenis_file';
    protected $primaryKey = 'jenisfile_id';

    protected $fillable = [
        'jenisfile',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
