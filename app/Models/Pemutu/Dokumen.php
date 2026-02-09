<?php
namespace App\Models\Pemutu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table      = 'pemutu_dokumen';
    protected $primaryKey = 'dok_id';
    protected $fillable   = [
        'parent_doksub_id', // For potential future hierarchy if needed, though migration says nullable
        'parent_id',
        'level',
        'seq',
        'jenis',
        'judul',
        'kode',
        'periode',
        // 'tgl_berlaku', // Removed per request
        'std_is_staging',
        'std_amirtn_id',
        'std_jeniskriteria_id',
    ];
    public $timestamps = false; // Migration doesn't have timestamps

    protected $casts = [
        'tgl_berlaku'    => 'date',
        'std_is_staging' => 'boolean',
    ];

    // Relationships
    public function dokSubs()
    {
        return $this->hasMany(DokSub::class, 'dok_id', 'dok_id')->orderBy('seq');
    }

    // Hierarchy Relationships
    public function parent()
    {
        return $this->belongsTo(Dokumen::class, 'parent_id', 'dok_id');
    }

    public function parentDokSub()
    {
        return $this->belongsTo(DokSub::class, 'parent_doksub_id', 'doksub_id');
    }

    public function children()
    {
        return $this->hasMany(Dokumen::class, 'parent_id', 'dok_id')->orderBy('seq');
    }
}
