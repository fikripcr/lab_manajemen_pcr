<?php
namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabInventaris extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'lab_inventaris_penempatan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'inventaris_id',
        'lab_id',
        'kode_inventaris',
        'no_series',
        'tanggal_penempatan',
        'tanggal_penghapusan',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_penempatan'  => 'datetime',
        'tanggal_penghapusan' => 'datetime',
        'status'              => 'string',
    ];

    /**
     * Relationship: Lab Inventaris belongs to an Inventaris
     */
    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id', 'inventaris_id');
    }

    /**
     * Relationship: Lab Inventaris belongs to a Lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }

    /**
     * Generate unique kode inventaris
     */
    public static function generateKodeInventaris($labId, $inventarisId)
    {
        return \App\Helpers\Helper::generateKodeInventaris($labId, $inventarisId);
    }

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    /**
     * Accessor to get encrypted lab_id
     */
    public function getEncryptedLabIdAttribute()
    {
        return encryptId($this->lab_id);
    }

    /**
     * Accessor to get encrypted inventaris_id
     */
    public function getEncryptedInventarisIdAttribute()
    {
        return encryptId($this->inventaris_id);
    }
}
