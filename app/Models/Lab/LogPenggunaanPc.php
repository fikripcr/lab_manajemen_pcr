<?php
namespace App\Models\Lab;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogPenggunaanPc extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'lab_log_penggunaan_pcs';
    protected $primaryKey = 'log_penggunaan_pcs_id';

    protected $appends = ['encrypted_log_penggunaan_pcs_id'];

    public function getRouteKeyName()
    {
        return 'log_penggunaan_pcs_id';
    }

    protected $fillable = [
        'pc_assignment_id',
        'user_id',
        'jadwal_id',
        'lab_id',
        'status_pc',
        'kondisi',
        'catatan_umum',
        'waktu_isi', 'created_by', 'updated_by', 'deleted_by',

    ];

    protected $casts = [
        'waktu_isi' => 'datetime',
    ];

    /**
     * Relationship: Log belongs to a PC assignment
     */
    public function pcAssignment()
    {
        return $this->belongsTo(PcAssignment::class, 'pc_assignment_id');
    }

    /**
     * Relationship: Log belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Log belongs to a schedule
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalKuliah::class, 'jadwal_id');
    }

    /**
     * Relationship: Log belongs to a lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }

    /**
     * Accessor to get encrypted log_penggunaan_pcs_id
     */
    public function getEncryptedLogPenggunaanPcsIdAttribute()
    {
        return encryptId($this->log_penggunaan_pcs_id);
    }

    /**
     * Accessor to get encrypted lab_id
     */
    public function getEncryptedLabIdAttribute()
    {
        return encryptId($this->lab_id);
    }

    /**
     * Accessor to get encrypted user_id
     */
    public function getEncryptedUserIdAttribute()
    {
        return encryptId($this->user_id);
    }

    /**
     * Accessor to get encrypted jadwal_id
     */
    public function getEncryptedJadwalIdAttribute()
    {
        return encryptId($this->jadwal_id);
    }
}
