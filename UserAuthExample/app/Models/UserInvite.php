<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInvite extends Model
{
    protected $table = 'user_invite';
    protected $primaryKey = 'user_id';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'token', 'email_address', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function getInviteStatusByEmail($email)
    {
        $res = \DB::table('user_invite')
                    ->where('email_address', $email)
                    ->first();

        if($res)
        {
            return $res['status'];
        }

        return false;
    }
}
