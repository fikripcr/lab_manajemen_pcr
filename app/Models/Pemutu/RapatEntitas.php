<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapatEntitas extends Model
{
    use HasFactory, Blameable, HashidBinding;

    protected $table      = 'pemutu_rapat_entitas';
    protected $primaryKey = 'rapatentitas_id';

    protected $fillable = [
        'rapat_id',
        'model',
        'model_id',
        'keterangan',
    ];

    /**
     * Get the rapat for the entitas.
     */
    public function rapat()
    {
        return $this->belongsTo(Rapat::class, 'rapat_id');
    }
}
