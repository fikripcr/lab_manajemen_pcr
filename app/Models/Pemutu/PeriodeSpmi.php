<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodeSpmi extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pemutu_periode_spmi';
    protected $primaryKey = 'periodespmi_id';

    protected $fillable = [
        'periode',
        'jenis_periode',
        'penetapan_awal',
        'penetapan_akhir',
        'ed_awal',
        'ed_akhir',
        'ami_awal',
        'ami_akhir',
        'pengendalian_awal',
        'pengendalian_akhir',
        'peningkatan_awal',
        'peningkatan_akhir',
    ];

    protected $casts = [
        'penetapan_awal'     => 'date',
        'penetapan_akhir'    => 'date',
        'ed_awal'            => 'date',
        'ed_akhir'           => 'date',
        'ami_awal'           => 'date',
        'ami_akhir'          => 'date',
        'pengendalian_awal'  => 'date',
        'pengendalian_akhir' => 'date',
        'peningkatan_awal'   => 'date',
        'peningkatan_akhir'  => 'date',
    ];
}
