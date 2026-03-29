<?php

namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Label extends Model
{
    use Blameable, HasFactory, HashidBinding, SoftDeletes;

    protected $table = 'pemutu_label';

    protected $primaryKey = 'label_id';

    protected $appends = ['encrypted_label_id'];

    public function getRouteKeyName()
    {
        return 'label_id';
    }

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'color',
        'created_by',
        'updated_by', 'deleted_by',

    ];

    public $timestamps = false;

    public function getEncryptedLabelIdAttribute()
    {
        return encryptId($this->label_id);
    }

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Label::class, 'parent_id', 'label_id');
    }

    public function children()
    {
        return $this->hasMany(Label::class, 'parent_id', 'label_id');
    }
}
