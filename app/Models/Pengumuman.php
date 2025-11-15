<?php
namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use HasFactory, HasMedia, SoftDeletes;

    protected $table      = 'pengumuman';
    protected $primaryKey = 'pengumuman_id';

    protected $fillable = [
        'judul',
        'isi',
        'jenis',
        'penulis_id',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published'  => 'boolean',
        'jenis'         => 'string',
        'pengumuman_id' => 'string',
    ];

    /**
     * Relationship: Announcement belongs to a penulis (user)
     */
    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    /**
     * Relationship: Announcement has many media
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Get the value of the model's route key.
     */
    public function getRouteKey()
    {
        return encryptId($this->getKey());
    }

    /**
     * Get first media by collection name.
     */
    public function getFirstMediaByCollection(string $collectionName)
    {
        return $this->media()->where('collection_name', $collectionName)->first();
    }

    /**
     * Get media by collection name.
     */
    public function getMediaByCollection(string $collectionName)
    {
        return $this->media()->where('collection_name', $collectionName)->get();
    }
}
