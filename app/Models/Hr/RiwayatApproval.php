<?php
namespace App\Models\Hr;

use App\Models\Hr\Lembur;
use App\Models\Hr\Perizinan;
use App\Models\Shared\Pegawai;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatApproval extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_riwayat_approval';
    protected $primaryKey = 'riwayatapproval_id';

    protected $appends = ['encrypted_riwayatapproval_id'];

    public function getRouteKeyName()
    {
        return 'riwayatapproval_id';
    }

    public function getEncryptedRiwayatapprovalIdAttribute()
    {
        return encryptId($this->riwayatapproval_id);
    }

    protected $fillable = [
        'model',
        'model_id',
        'status', // Draft, Pending, Approved, Rejected
        'pejabat',
        'jenis_jabatan',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the parent model (polymorphic).
     */
    public function subject()
    {
        return $this->morphTo('subject', 'model', 'model_id');
    }

    /**
     * Get the pegawai associated with the approval subject.
     */
    public function getPegawaiAttribute()
    {
        $subject = $this->subject;
        if (! $subject) {
            return null;
        }

        if ($subject instanceof Perizinan) {
            return $subject->pengusulPegawai;
        }

        if ($subject instanceof Lembur) {
            return $subject->pengusul;
        }

        // Default check if subject has 'pegawai' relationship or is Pegawai
        if ($subject instanceof Pegawai) {
            return $subject;
        }

        if (method_exists($subject, 'pegawai')) {
            return $subject->pegawai;
        }

        // Check for pegawai_id
        if (isset($subject->pegawai_id)) {
            // Attempt to load if not relation
            return Pegawai::find($subject->pegawai_id);
        }

        return null;
    }
}
