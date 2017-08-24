<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class level extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'levels';

    public function user()
    {
        return $this->hasMany('App\Models\user');
    }
}
