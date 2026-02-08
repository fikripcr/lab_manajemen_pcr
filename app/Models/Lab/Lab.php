<?php
namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Lab extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $table      = 'labs';
    protected $primaryKey = 'lab_id';

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'description',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'lab_id'   => 'string',
    ];

    /**
     * Relationship: Lab has many mata kuliah
     */
    public function mataKuliahs()
    {
        return $this->hasManyThrough(MataKuliah::class, JadwalKuliah::class, 'lab_id', 'id', 'lab_id', 'mata_kuliah_id');
    }

    /**
     * Relationship: Lab has many schedules
     */
    public function jadwals()
    {
        return $this->hasMany(JadwalKuliah::class, 'lab_id');
    }

    /**
     * Relationship: Lab has many PC assignments
     */
    public function pcAssignments()
    {
        return $this->hasMany(PcAssignment::class, 'lab_id');
    }

    /**
     * Relationship: Lab has many PC usage logs
     */
    public function logPenggunaanPcs()
    {
        return $this->hasMany(LogPenggunaanPc::class, 'lab_id');
    }

    /**
     * Relationship: Lab has many lab usage logs
     */
    public function logPenggunaanLabs()
    {
        return $this->hasMany(LogPenggunaanLab::class, 'lab_id');
    }

    /**
     * Relationship: Lab has many events
     */
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'lab_id');
    }

    /**
     * Relationship: Lab has many inventory assignments through lab_inventaris
     */
    public function inventaris()
    {
        return $this->belongsToMany(Inventaris::class, 'lab_inventaris', 'lab_id', 'inventaris_id')
            ->withPivot(['kode_inventaris', 'no_series', 'tanggal_penempatan', 'tanggal_penghapusan', 'status', 'keterangan'])
            ->withTimestamps();
    }

    /**
     * Relationship: Lab has many lab_inventaris entries
     */
    public function labInventaris()
    {
        return $this->hasMany(LabInventaris::class, 'lab_id', 'lab_id');
    }

    /**
     * Relationship: Lab has many team members
     */
    public function labTeams()
    {
        return $this->hasMany(LabTeam::class, 'lab_id', 'lab_id');
    }

    /**
     * Relationship: Lab has many media entries
     */
    public function labMedia()
    {
        return $this->hasMany(LabMedia::class, 'lab_id', 'lab_id');
    }

    /**
     * Spatie Media Library Integration
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('lab_images')
            ->singleFile()
            ->useFallbackUrl('/assets-admin/img/default-lab-image.jpg')
            ->useFallbackPath(public_path('/assets-admin/img/default-lab-image.jpg'));

        $this->addMediaCollection('lab_attachments')
            ->useFallbackUrl('/assets-admin/img/default-attachment.jpg')
            ->useFallbackPath(public_path('/assets-admin/img/default-attachment.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Fit::Crop, 150, 150)
            ->optimize()
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->fit(Fit::Crop, 400, 400)
            ->optimize()
            ->nonQueued();
    }

    /**
     * Get active team members for this lab
     */
    public function getActiveTeamMembers()
    {
        return $this->labTeams()
            ->where('is_active', true)
            ->with('user')
            ->get();
    }

    /**
     * Accessor to get encrypted lab_id
     */
    public function getEncryptedLabIdAttribute()
    {
        return encryptId($this->lab_id);
    }
}
