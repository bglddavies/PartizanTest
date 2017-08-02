<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Organisation extends Model
{
    protected $table = 'organisation';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'al1', 'al2', 'town', 'region', 'postcode', 'country', 'vat_number', 'company_number', 'phone_number', 'fax_number', 'company_type_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function getAdminOrganisation()
    {
        $admOrg = ConfigDB::where('key', 'adm_org')->first();
        if($admOrg)
        {
            $admOrg = intval($admOrg['value']);
            $model = Organisation::where('id', $admOrg)->first();

            if($model)
            {
                return $model;
            }
        }

        return false;
    }

    public static function getOrganisationsNotAdmin()
    {
        $admOrg = ConfigDB::where('key', 'adm_org')->first();
        if($admOrg)
        {
            $admOrg = intval($admOrg['value']);
            $orgs = \DB::table('organisation')->where('id', '!=', $admOrg)->get();
            return $orgs;
        }
        return false;
    }

    public static function getOrganisationsNotAdminDT($start = 0, $length = 10, $search = '')
    {
        $admOrg = ConfigDB::where('key', 'adm_org')->first();
        if($admOrg)
        {
            $admOrg = intval($admOrg['value']);
            $orgs = \DB::table('organisation')
                ->where('name', 'like', '%'.$search.'%')
                ->where('id', '!=', $admOrg)
                ->skip($start)
                ->take($length)
                ->get();
            return $orgs;
        }
        return false;
    }

    public static function getOrganisationsByAssigneeId($id)
    {
        $results = \DB::table('member_organisations')->where('member_id', $id)->get();
        if($results)
        {
            $orgIDs = [];

            foreach($results as $result)
            {
                $orgIDs[] = $result->organisation_id;
            }

            $returnOrgs = \DB::table('organisation')->whereIn('id', $orgIDs)->get();

            return $returnOrgs;
        }

        return false;
    }

    public static function getAssigneesByOrganisationId($id)
    {
        $results = \DB::table('member_organisations')->where('organisation_id', $id)->get();
        if($results)
        {
            $memberIDs = [];
            foreach($results as $result)
            {
                $memberIDs[] = $result->member_id;
            }

            $returnMembers = \DB::table('user')->whereIn('id', $memberIDs)->where('activated', true)->get();

            return $returnMembers;
        }
    }

    public static function assignOrganisationsToMember($orgs, $member_id)
    {
        $admOrg = Organisation::getAdminOrganisation();
        $member = User::where('id', $member_id)->where('user_role', 'member')->first();
        $dt = new \DateTime();
        $dtf = $dt->format('Y-m-d H:i:s');
        if($member)
        {
            \DB::table('member_organisations')->where('member_id', $member->id)->delete();
            foreach($orgs as $org)
            {
                if($org != $admOrg->id)
                {
                    \DB::table('member_organisations')->insert(array('member_id'=>$member->id, 'organisation_id'=>$org, 'created_at'=>$dtf, 'updated_at'=>$dtf));
                }
            }
            return true;
        }
        return false;
    }

    public static function deleteOrganisationDataByOrganisationID($id)
    {
        \DB::table('organisation_related_datetime')->where('organisation_id', $id)->delete();
        return true;
    }

    public static function getOrganisationsForAuto($adtID)
    {
        $adt = AutomaticDocumentTransfer::where('id', $adtID)->first();
        $dtt = DocumentTransferTemplate::where('id', $adt->transfer_template_id)->first();

        if($dtt)
        {
            //HUGE MESS OF A QUERY
        }

        return false;
    }
}
