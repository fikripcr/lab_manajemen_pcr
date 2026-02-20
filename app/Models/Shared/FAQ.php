<?php
namespace App\Models\Shared;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FAQ extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table   = 'faqs';
    protected $appends = ['encrypted_faq_id'];

    public function getEncryptedFaqIdAttribute()
    {
        return encryptId($this->id);
    }

    protected $fillable = [
        'question',
        'answer',
        'category',
        'seq',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
