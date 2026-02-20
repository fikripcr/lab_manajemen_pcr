<?php
namespace App\Models\Shared;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PublicPage extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding, InteractsWithMedia;

    protected $primaryKey = 'page_id';
    protected $appends    = ['encrypted_page_id'];

    public function getEncryptedPageIdAttribute()
    {
        return encryptId($this->page_id);
    }

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_desc',
        'meta_keywords',
        'is_published',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_image')
            ->singleFile();

        $this->addMediaCollection('attachments');
    }
}
