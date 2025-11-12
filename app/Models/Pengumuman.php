<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory, HasMedia;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'jenis',
        'penulis_id',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'jenis' => 'string',
    ];

    /**
     * Relationship: Announcement belongs to a penulis (user)
     */
    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }
    
    /**
     * Get the value of the model's route key.
     */
    public function getRouteKey()
    {
        return encryptId($this->getKey());
    }
}