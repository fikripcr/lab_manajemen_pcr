<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabSlideshow extends Model
{
    use HasFactory;

    protected $table = 'lab_slideshows';

    protected $fillable = [
        'lab_id',
        'judul',
        'deskripsi',
        'gambar_path',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Slideshow belongs to a lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }
}