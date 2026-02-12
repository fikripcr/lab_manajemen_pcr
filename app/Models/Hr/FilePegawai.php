<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FilePegawai extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding, InteractsWithMedia;

    protected $table      = 'hr_file_pegawai';
    protected $primaryKey = 'filepegawai_id';

    protected $fillable = [
        'pegawai_id',
        'jenisfile_id',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Relationship: Linked to the employee.
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    /**
     * Relationship: Linked to the file category.
     */
    public function jenisFile()
    {
        return $this->belongsTo(JenisFile::class, 'jenisfile_id', 'jenisfile_id');
    }

    /**
     * Spatie Media Library: Register collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file_pegawai')
            ->singleFile();
    }
}
