<?php
namespace App\Models\Eoffice;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananStatus extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_layanan_status';
    protected $primaryKey = 'layananstatus_id';

    public function getRouteKeyName()
    {
        return 'layananstatus_id';
    }

    protected $fillable = [
        'layanan_id',
        'status_layanan',
        'keterangan',
        'file_lampiran',
        'disposisi_info',
        'done_at',
        'done_duration',
        'done_by_email',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'disposisi_info' => 'json',
        'done_at'        => 'datetime',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'layanan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
