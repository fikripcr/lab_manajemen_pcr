<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_feedback';
    protected $primaryKey = 'feedback_id';

    public function getRouteKeyName()
    {
        return 'feedback_id';
    }

    protected $fillable = [
        'layanan_id',
        'rating',
        'feedback',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'layanan_id');
    }
}
