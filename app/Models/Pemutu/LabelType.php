<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelType extends Model
{
    use HasFactory, Blameable, HashidBinding;

    protected $table = 'pemutu_label_types';
    protected $primaryKey = 'labeltype_id';
    protected $appends = ['encrypted_labeltype_id'];
    protected $fillable = [
        'name', 
        'description', 
        'color',
        'created_by',
        'updated_by',
    ];

    public function getEncryptedLabeltypeIdAttribute()
    {
        return encryptId($this->labeltype_id);
    }

    // Relationships
    public function labels()
    {
        return $this->hasMany(Label::class, 'type_id', 'labeltype_id');
    }
}
