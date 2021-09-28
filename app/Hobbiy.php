<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hobbiy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hobbies';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name'
    ];
}
