<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapatAgenda extends Model
{
    use HasFactory, Blameable, HashidBinding, UuidTrait;

    protected $table      = 'pemutu_rapat_agenda';
    protected $primaryKey = 'rapatagenda_id';

    protected $fillable = [
        'rapat_id',
        'judul_agenda',
        'isi',
        'seq',
    ];

    /**
     * Get the rapat for the agenda.
     */
    public function rapat()
    {
        return $this->belongsTo(Rapat::class, 'rapat_id');
    }
}
