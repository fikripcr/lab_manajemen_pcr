<?php
namespace App\Models\Pemutu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class IndikatorPersonil extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table    = 'pemutu_indikator_personil';
    protected $fillable = [
        'personil_id',
        'indikator_id',
        'year',
        'semester',
        'weight',
        'target_value',        'created_by',        'updated_by',        'deleted_by',
    
    
    
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
