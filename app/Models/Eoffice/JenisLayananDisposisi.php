<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisLayananDisposisi extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_jenis_layanan_disposisi';
    protected $primaryKey = 'jldisposisi_id';

    public function getRouteKeyName()
    {
        return 'jldisposisi_id';
    }

    protected $fillable = [
        'jenislayanan_id',
        'seq',
        'model',
        'value',
        'text',
        'is_notify_email',
        'batas_pengerjaan',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_notify_email' => 'boolean',
    ];

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenislayanan_id', 'jenislayanan_id');
    }
}
