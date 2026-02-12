<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class LemburWaktu extends Model
{
    use SoftDeletes, Blameable, HashidBinding;
    //
}
