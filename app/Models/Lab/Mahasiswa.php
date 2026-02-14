<?php
namespace App\Models\Lab;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'lab_mahasiswa';
    protected $primaryKey = 'mahasiswa_id';

    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'email',
        'program_studi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the user that owns the mahasiswa.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the student (as user) for Surat Bebas Lab.
     */
    public function suratBebasLab()
    {
        return $this->hasMany(SuratBebasLab::class, 'student_id', 'user_id');
    }
}
