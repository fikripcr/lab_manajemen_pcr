<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posisi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_posisi';
    protected $primaryKey = 'posisi_id';

    protected $fillable = [
        'posisi',
        'alias',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
