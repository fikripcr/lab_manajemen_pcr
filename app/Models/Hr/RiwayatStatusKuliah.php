<?php

namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatStatusKuliah extends Model
{
    use Blameable, HashidBinding, SoftDeletes;
    //
}
