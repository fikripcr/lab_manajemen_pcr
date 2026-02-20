<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GolonganInpassing extends Model
{
    use SoftDeletes, Blameable, HashidBinding;
    protected $table      = 'hr_golongan_inpassing';
    protected $primaryKey = 'gol_inpassing_id';
    protected $guarded    = ['gol_inpassing_id'];

    protected $appends = ['encrypted_gol_inpassing_id'];

    public function getRouteKeyName()
    {
        return 'gol_inpassing_id';
    }

    public function getEncryptedGolInpassingIdAttribute()
    {
        return encryptId($this->gol_inpassing_id);
    }
}
