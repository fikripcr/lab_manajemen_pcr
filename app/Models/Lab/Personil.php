<?php
namespace App\Models\Lab;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personil extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'lab_personil';
    protected $primaryKey = 'personil_id';

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'nip',
        'jabatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the user that owns the personil.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
