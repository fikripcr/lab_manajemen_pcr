<?php
namespace App\Models\Pemutu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelType extends Model
{
    use HasFactory;

    protected $table      = 'pemutu_label_types';
    protected $primaryKey = 'labeltype_id';
    protected $fillable   = ['name', 'description', 'color'];

    // Relationships
    public function labels()
    {
        return $this->hasMany(Label::class, 'type_id', 'labeltype_id');
    }
}
