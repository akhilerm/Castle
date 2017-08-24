<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    public function level()
    {
        return $this->belongsTo('App\Models\level');
    }
}
