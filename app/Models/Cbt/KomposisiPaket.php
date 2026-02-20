<?php
namespace App\Models\Cbt;

use App\Models\Cbt\PaketUjian;
use App\Models\Cbt\Soal;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Model;

class KomposisiPaket extends Model
{
    use HashidBinding;
    protected $table      = 'cbt_komposisi_paket';
    protected $primaryKey = 'komposisi_paket_id';
    protected $appends    = ['encrypted_komposisi_paket_id'];

    protected $fillable = [
        'paket_id',
        'soal_id',
        'urutan_tampil',
    ];

    public function getEncryptedKomposisiPaketIdAttribute()
    {
        return encryptId($this->komposisi_paket_id);
    }

    public function getRouteKeyName()
    {
        return 'komposisi_paket_id';
    }

    public function paket()
    {
        return $this->belongsTo(PaketUjian::class, 'paket_id');
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}
