<?php

namespace App\Models\Sys;

use App\Models\Hr\Pegawai;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model SysApproval - Global Approval System
 * 
 * Digunakan untuk tracking approval workflow di berbagai modul (polymorphic):
 * - Pemutu (Dokumen SPMI)
 * - HR (Perizinan, Lembur)
 * - PMB (Pendaftaran)
 * - Lab (Request Software, Surat Bebas, Kegiatan)
 * - Dan modul lainnya
 * 
 * Status Flow:
 * 1. Draft → Approver ditetapkan tapi belum mulai approval
 * 2. Pending → Menunggu approval dari pegawai yang ditunjuk (DEFAULT saat create)
 * 3. Approved → Approver menyetujui dokumen
 * 4. Rejected → Approver menolak dokumen
 * 
 * Workflow:
 * - Saat approver ditambahkan → status = 'Pending'
 * - Approver melakukan approve → status = 'Approved'
 * - Approver melakukan reject → status = 'Rejected'
 * - Dokumen SAH jika SEMUA approver = 'Approved'
 * 
 * @package App\Models\Sys
 */
class SysApproval extends Model
{
    use Blameable, HasFactory, HashidBinding, SoftDeletes;

    protected $table = 'sys_approvals';

    protected $primaryKey = 'sys_approval_id';

    protected $appends = ['encrypted_sys_approval_id'];

    public function getRouteKeyName()
    {
        return 'sys_approval_id';
    }

    public function getEncryptedSysApprovalIdAttribute()
    {
        return encryptId($this->sys_approval_id);
    }

    /**
     * The attributes that are mass assignable.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'model',                    // Class name (e.g., 'App\Models\Pemutu\Dokumen')
        'model_id',                 // ID dari model yang di-approve
        'pegawai_id',               // ID approver (pegawai yang ditunjuk)
        'pejabat',                  // Nama approver
        'jabatan',                  // Jabatan approver
        'status',                   // Draft, Pending, Approved, Rejected (default: Pending)
        'catatan',                  // Catatan approval/rejection
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'Pending',  // Default status saat approver ditambahkan
    ];

    /**
     * Get the parent approvalable model (polymorphic).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo('subject', 'model', 'model_id');
    }
    
    /**
     * Get the pegawai (approver) details.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
    
    /**
     * Scope untuk mendapatkan approval yang pending.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }
    
    /**
     * Scope untuk mendapatkan approval yang approved.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }
    
    /**
     * Scope untuk mendapatkan approval yang rejected.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }
}
