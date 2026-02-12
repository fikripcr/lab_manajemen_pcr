<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananDiskusi extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_layanan_diskusi';
    protected $primaryKey = 'diskusi_id';

    public function getRouteKeyName()
    {
        return 'diskusi_id';
    }

    protected $fillable = [
        'layanan_id',
        'user_id',
        'pesan',
        'file_lampiran',
        'status_pengirim',
        'created_by_email',        'created_by',        'updated_by',        'deleted_by',
    
    
    
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'layanan_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
