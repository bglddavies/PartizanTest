<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','first_name', 'middle_name', 'last_name',
        'contact_role', 'organisation_id',
        'email', 'password', 'secret_question', 'secret_answer',
        'contact_tel','fax','user_role','activated', 'remember_token',
        'personal_returns_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function getActiveUsersByOrganisation($id)
    {
        $users = \DB::table('users')->where('organisation_id', $id)->where('activated', true)->get();
        return $users;
    }

    public static function getActiveUsersByOrganisationDT($id, $start = 0, $length = 10, $search = '')
    {
        $users = \DB::table('users')
            ->where('email', 'like', '%'.$search.'%')
            ->where('organisation_id', $id)
            ->where('activated', true)
            ->skip($start)
            ->take($length)
            ->get();
        return $users;
    }

    public static function getInactiveUsersByOrganisation($id)
    {
        $users = \DB::table('users')->where('organisation_id', $id)->where('activated', false)->get();
        return $users;
    }

    public static function getInactiveUsersByOrganisationDT($id, $start = 0, $length = 10, $search = '')
    {
        $users = \DB::table('users')
            ->where('email', 'like', '%'.$search.'%')
            ->where('organisation_id', $id)
            ->where('activated', false)
            ->skip($start)
            ->take($length)
            ->get();
        return $users;
    }

    public static function getAdminUserCount()
    {
        $adminCount = \DB::table('users')->where('user_role', 'back')->where('activated', true)->count();
        return $adminCount;
    }

    public static function deleteFrontUsersByOrganisationID($id)
    {
        $users = \DB::table('users')->where('user_role', 'front')->where('organisation_id', $id)->get();
        foreach($users as $user)
        {
            \DB::table('user_invite')->where('user_id', $user->id)->delete();
            \DB::table('client_related_datetime')->where('user_id', $user->id)->delete();
            \DB::table('users')->where('id', $user->id)->delete();
        }
        return true;
    }
}
