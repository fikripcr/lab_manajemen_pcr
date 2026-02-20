<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenApproval extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pemutu_dok_approval';
    protected $primaryKey = 'dokapproval_id';

    protected $appends = ['encrypted_dokapproval_id'];

    public function getRouteKeyName()
    {
        return 'dokapproval_id';
    }

    public function getEncryptedDokapprovalIdAttribute()
    {
        return encryptId($this->dokapproval_id);
    }

    protected $fillable = [
        'dok_id',
        'proses',
        'pegawai_id',
        'jabatan',
    ];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dok_id', 'dok_id');
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\Shared\Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    public function statuses()
    {
        return $this->hasMany(DokumenApprovalStatus::class, 'dokapproval_id', 'dokapproval_id');
    }
}
