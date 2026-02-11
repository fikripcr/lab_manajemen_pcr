<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_pegawai';
    protected $primaryKey = 'pegawai_id';
    protected $fillable   = [
        'user_id',
        'nip',
        'nama',
        'inisial',
        'email',
        'departemen',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getRouteKeyName()
    {
        return 'pegawai_id';
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
