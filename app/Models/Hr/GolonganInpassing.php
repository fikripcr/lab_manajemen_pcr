<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class GolonganInpassing extends Model
{
    protected $table = 'hr_golongan_inpassing';
    protected $primaryKey = 'gol_inpassing_id';
    protected $guarded = ['gol_inpassing_id'];
}
