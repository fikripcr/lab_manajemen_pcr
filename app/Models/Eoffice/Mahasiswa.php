<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_mahasiswa';
    protected $primaryKey = 'mahasiswa_id';
    protected $fillable   = [
        'user_id',
        'nim',
        'nama',
        'email',
        'program_studi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getRouteKeyName()
    {
        return 'mahasiswa_id';
    }
}
