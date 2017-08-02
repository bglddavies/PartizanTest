<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisationType extends Model
{
    protected $table = 'organisation_type';
    public $timestamps;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function getAllTypes()
    {
        $types = \DB::table('organisation_type')
                        ->get();

        return $types;
    }
}
