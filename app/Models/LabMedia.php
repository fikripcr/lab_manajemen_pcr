<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabMedia extends Model
{
    use HasFactory;

    protected $table = 'lab_media';
    protected $primaryKey = 'lab_media_id';

    protected $fillable = [
        'lab_id',
        'media_id',
        'judul',
        'keterangan',
    ];

    protected $casts = [
        'lab_id' => 'integer',
        'media_id' => 'integer',
    ];

    /**
     * Relationship: Lab Media belongs to a lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }

    /**
     * Relationship: Lab Media belongs to a media
     */
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Accessor to get encrypted lab_media_id
     */
    public function getEncryptedLabMediaIdAttribute()
    {
        return encryptId($this->lab_media_id);
    }

    /**
     * Accessor to get encrypted lab_id
     */
    public function getEncryptedLabIdAttribute()
    {
        return encryptId($this->lab_id);
    }

    /**
     * Accessor to get encrypted media_id
     */
    public function getEncryptedMediaIdAttribute()
    {
        return encryptId($this->media_id);
    }
}
