<?php
namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class LabTeam extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'lab_teams';
    protected $primaryKey = 'id';

    protected $fillable = [
        'lab_id',
        'user_id',
        'jabatan',
        'is_active',
        'tanggal_mulai',
        'tanggal_selesai',        'created_by',        'updated_by',        'deleted_by',
    
    
    
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'tanggal_mulai'   => 'datetime',
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
     * Accessor to get encrypted user_id
     */
    public function getEncryptedUserIdAttribute()
    {
        return encryptId($this->user_id);
    }
}
