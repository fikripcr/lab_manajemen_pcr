<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prodi extends Model
{
    use HasFactory, SoftDeletes, Blameable;

    protected $table      = 'hr_prodi';
    protected $primaryKey = 'prodi_id';

    protected $fillable = [
        'nama_prodi',
        'jenjang',
        'abbr',
        'alias',
        'departemen_id',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id', 'departemen_id');
    }
}
