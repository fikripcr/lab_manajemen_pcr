<?php
namespace App\Models\Lab;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodSoftRequest extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'lab_periode_softrequest';
    protected $primaryKey = 'periodsoftreq_id';

    protected $fillable = [
        'semester_id',
        'nama_periode',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /**
     * Relationship: Period belongs to a semester
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'semester_id');
    }

    /**
     * Relationship: Period has many software requests
     */
    public function softwareRequests()
    {
        return $this->hasMany(RequestSoftware::class, 'periodsoftreq_id', 'periodsoftreq_id');
    }

    /**
     * Accessor to get encrypted periodsoftreq_id
     */
    public function getEncryptedPeriodsoftreqIdAttribute()
    {
        return encryptId($this->periodsoftreq_id);
    }
}
