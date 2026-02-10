<?php
namespace App\Models\Pemutu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorPersonil extends Model
{
    use HasFactory;

    protected $table    = 'pemutu_indikator_personil';
    protected $fillable = [
        'personil_id',
        'indikator_id',
        'year',
        'semester',
        'weight',
        'target_value',
    ];

    public function personil()
    {
        return $this->belongsTo(Personil::class, 'personil_id', 'personil_id');
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_id', 'indikator_id');
    }
}
