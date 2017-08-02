<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessfulLogin extends Model
{
    protected $table = 'successful_login';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'dt', 'ip', 'user_agent'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
