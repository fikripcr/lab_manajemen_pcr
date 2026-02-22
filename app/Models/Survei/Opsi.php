<?php
namespace App\Models\Survei;

use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opsi extends Model
{
    use HasFactory, HashidBinding;

    protected $table = 'survei_opsi';
    protected $primaryKey = 'opsi_id';
    protected $appends = ['encrypted_opsi_id'];

    public function getRouteKeyName()
    {
        return 'opsi_id';
    }

    public function getEncryptedOpsiIdAttribute()
    {
        return encryptId($this->opsi_id);
    }

    protected $fillable = [
        'pertanyaan_id',
        'label',
        'nilai_tersimpan',
        'bobot_skor',
        'urutan',
        'next_pertanyaan_id',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    public function nextPertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'next_pertanyaan_id');
    }
}
