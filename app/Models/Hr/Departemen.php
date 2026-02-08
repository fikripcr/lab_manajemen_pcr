<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departemen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_departemen';
    protected $primaryKey = 'departemen_id';

    protected $fillable = [
        'departemen',
        'abbr',
        'alias',
        'jurusan_id',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
