<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class GolonganInpassing extends Model
{
    use SoftDeletes, Blameable, HashidBinding;
    protected $table = 'hr_golongan_inpassing';
    protected $primaryKey = 'gol_inpassing_id';
    protected $guarded = ['gol_inpassing_id'];
}
