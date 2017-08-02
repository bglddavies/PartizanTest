<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigDB extends Model
{
    protected $table = 'config';
    public $timestamps;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'value'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
