<?php
namespace App\Models\Shared;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicMenu extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'parent_id',
        'title',
        'url',
        'type',
        'page_id',
        'position',
        'target',
        'sequence',
        'is_active',
    ];

    public function parent()
    {
        return $this->belongsTo(PublicMenu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(PublicMenu::class, 'parent_id')->orderBy('sequence');
    }

    public function page()
    {
        return $this->belongsTo(PublicPage::class, 'page_id');
    }
}
