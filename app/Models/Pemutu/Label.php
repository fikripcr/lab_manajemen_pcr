<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Label extends Model
{
    use HasFactory, Blameable, HashidBinding, SoftDeletes;

    protected $table      = 'pemutu_label';
    protected $primaryKey = 'label_id';
    protected $appends    = ['encrypted_label_id'];

    public function getRouteKeyName()
    {
        return 'label_id';
    }
    protected $fillable = [
        'type_id',
        'name',
        'slug',
        'description',
        'created_by',
        'updated_by', 'deleted_by',

    ];
    public $timestamps = false;

    public function getEncryptedLabelIdAttribute()
    {
        return encryptId($this->label_id);
    }

    // Relationships
    public function type()
    {
        return $this->belongsTo(LabelType::class, 'type_id', 'labeltype_id');
    }
}
