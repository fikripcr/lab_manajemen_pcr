<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;

class SysDashboardView extends Model
{
    protected $table = 'vw_sys_dashboard';
    public $timestamps = false;
    protected $fillable = [];

    // All attributes are read-only since this is a view
    protected $guarded = ['*'];

    /**
     * Disable model updates since this is a view
     */
    public function save(array $options = [])
    {
        throw new \Exception('Cannot save to a database view');
    }

    public function update(array $attributes = [], array $options = [])
    {
        throw new \Exception('Cannot update a database view');
    }

    public function delete()
    {
        throw new \Exception('Cannot delete from a database view');
    }
}