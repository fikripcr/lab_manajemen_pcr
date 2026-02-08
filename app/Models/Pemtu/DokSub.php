<?php
namespace App\Models\Pemtu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokSub extends Model
{
    use HasFactory;

    protected $table      = 'pemutu_dok_sub';
    protected $primaryKey = 'doksub_id';
    protected $fillable   = [
        'dok_id',
        'judul',
        'isi',
        'seq',
    ];
    public $timestamps = false;

    // Relationships
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dok_id', 'dok_id');
    }

    public function indikators()
    {
        return $this->hasMany(Indikator::class, 'doksub_id', 'doksub_id')->orderBy('seq');
    }

    public function childDokumens()
    {
        return $this->hasMany(Dokumen::class, 'parent_doksub_id', 'doksub_id')->orderBy('seq');
    }
}
