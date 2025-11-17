<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTeam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lab_teams';
    protected $primaryKey = 'id';

    protected $fillable = [
        'lab_id',
        'user_id',
        'jabatan',
        'is_active',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    /**
     * Relationship: Lab Team belongs to a Lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'lab_id');
    }

    /**
     * Relationship: Lab Team belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}