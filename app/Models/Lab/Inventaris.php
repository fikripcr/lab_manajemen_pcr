<?php
namespace App\Models\Lab;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaris extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'lab_inventaris';
    protected $primaryKey = 'inventaris_id';

    protected $appends = ['encrypted_inventaris_id'];

    public function getRouteKeyName()
    {
        return 'inventaris_id';
    }

    protected $fillable = [
        'nama_alat',
        'jenis_alat',
        'kondisi_terakhir',
        'tanggal_pengecekan', 'created_by', 'updated_by', 'deleted_by',

    ];

    protected $casts = [
        'tanggal_pengecekan' => 'date',
    ];

    /**
     * Relationship: Inventory has many lab assignments through lab_inventaris
     */
    public function labs()
    {
        return $this->belongsToMany(Lab::class, 'lab_inventaris_penempatan', 'inventaris_id', 'lab_id')
            ->withPivot(['kode_inventaris', 'no_series', 'tanggal_penempatan', 'tanggal_penghapusan', 'status', 'keterangan'])
            ->withTimestamps();
    }

    /**
     * Get the active lab for this inventory (HasOneThrough or custom)
     * For LaporanKerusakan, it calls `$row->inventaris->lab->name`.
     */
    public function lab()
    {
        // Many-to-many relationship where we only want the active one
        return $this->belongsToMany(Lab::class, 'lab_inventaris_penempatan', 'inventaris_id', 'lab_id')
                    ->wherePivot('status', 'active')
                    ->withPivot(['kode_inventaris']);
    }

    /**
     * Relationship: Inventory has many lab_inventaris entries
     */
    public function labInventaris()
    {
        return $this->hasMany(LabInventaris::class, 'inventaris_id', 'inventaris_id');
    }

    /**
     * Relationship: Inventory has many damage reports
     */
    public function laporanKerusakans()
    {
        return $this->hasMany(LaporanKerusakan::class, 'inventaris_id');
    }

    /**
     * Get current lab assignment for this inventory item
     */
    public function getCurrentLab()
    {
        return $this->labInventaris()
            ->where('status', 'active')
            ->latest('tanggal_penempatan')
            ->first();
    }

    /**
     * Accessor to get encrypted inventaris_id
     */
    public function getEncryptedInventarisIdAttribute()
    {
        return encryptId($this->inventaris_id);
    }
}
