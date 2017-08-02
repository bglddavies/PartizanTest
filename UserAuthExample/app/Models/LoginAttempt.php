<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $table = 'login_attempts';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function canRelog($id)
    {
        $now = new \DateTime();
        $now->modify('-1 hour');
        $nf = $now->format('Y-m-d H:i:s');
        $res = \DB::table('login_attempts')
                    ->where('created_at', '>=' , $nf)
                    ->where('user_id', $id)
                    ->count();

        if($res >= 5)
        {
            return false;
        }

        return true;
    }

    public static function clearAttempts($id)
    {
        \DB::table('login_attempts')
                ->where('user_id', $id)
                ->delete();
    }
}
