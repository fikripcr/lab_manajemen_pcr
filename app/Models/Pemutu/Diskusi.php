<?php

namespace App\Models\Pemutu;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Diskusi extends Model
{
    protected $table      = 'pemutu_diskusi';
    protected $primaryKey = 'diskusi_id';

    protected $fillable = [
        'pengirim_user_id',
        'jenis_pengirim',
        'jenis_diskusi',
        'model_type',
        'model_id',
        'isi',
        'attachment_file',
        'attachment_link',
        'is_done',
    ];

    protected $casts = [
        'attachment_link' => 'array',
        'is_done'         => 'boolean',
        'created_at'      => 'datetime',
    ];

    // Polymorphic owner
    public function model()
    {
        return $this->morphTo();
    }

    // Pengirim (user yang mengirim diskusi)
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_user_id', 'id');
    }
}
