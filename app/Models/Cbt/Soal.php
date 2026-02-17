<?php
namespace App\Models\Cbt;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soal extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'cbt_soal';

    protected $fillable = [
        'mata_uji_id',
        'tipe_soal',
        'konten_pertanyaan',
        'media_url',
        'tingkat_kesulitan',
        'is_aktif',
        'dibuat_oleh',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function mataUji()
    {
        return $this->belongsTo(MataUji::class, 'mata_uji_id');
    }

    public function opsiJawaban()
    {
        return $this->hasMany(OpsiJawaban::class, 'soal_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
