<?php
/**
 * Created by PhpStorm.
 * User: baglad
 * Date: 16/01/2017
 * Time: 14:35
 */

namespace app\Http\Controllers\Admin;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserInvite;

/**
 * Class AdminOrganisationController
 * @package app\Http\Controllers\Admin
 * @description Controls the Admin Organisations and associated members. Get data, set data, delete data.
 */

class AdminOrganisationController extends \App\Http\Controllers\Controller
{
    public function getActiveAdminOrganisationMembersDT()
    {
        $length = 10;
        $start = 0;
        $search = '';

        $admOrg = Organisation::getAdminOrganisation();

        if($admOrg)
        {
            if(\Input::has('start'))
            {
                $start = \Input::get('start');
            }
            if(\Input::has('length'))
            {
                $length = \Input::get('length');
            }
            if(\Input::has('search'))
            {
                $search = \Input::get('search');
                $search = $search['value'];
            }

            $users = User::getActiveUsersByOrganisationDT($admOrg->id, $start, $length, $search);
            if($users)
            {
                $total = 0;
                $output = [];
                foreach($users as $user)
                {
                    $total++;
                    switch($user->user_role)
                    {
                        case 'back':{
                            $user->user_role = 'Admin';
                            break;
                        }
                        case 'front':{
                            $user->user_role = 'WARNING';
                            break;
                        }
                        case 'member':{
                            $user->user_role = 'Member';
                            break;
                        }
                        default:{
                            $user->user_role = 'WARNING';
                        }
                    }
                    $output[] = array(
                        '0' => $user->email,
                        '1' => $user->first_name.' '.$user->last_name,
                        '2' => $user->contact_role,
                        '3' => $user->user_role,
                        '4' => '<a class="btn btn-info" href="/admin/members/edit/'.$user->id.'">Edit</a>',
                    );
                }

                $results = [];

                $results['data'] = $output;
                $results['recordsTotal'] = $total; //TOTAL UNFILTERED ENTRIES
                $results['recordsFiltered'] = $total; //FILTERED ENTRIES :: NUMBER OF ENTRIES ACTUALLY RETURNED

                return \Response::json($results,200);
            }
        }
    }

    public function getInactiveAdminOrganisationMembersDT()
    {
        $length = 10;
        $start = 0;
        $search = '';
        $admOrg = Organisation::getAdminOrganisation();

        if($admOrg)
        {
            if(\Input::has('start'))
            {
                $start = \Input::get('start');
            }
            if(\Input::has('length'))
            {
                $length = \Input::get('length');
            }
            if(\Input::has('search'))
            {
                $search = \Input::get('search');
                $search = $search['value'];
            }
            $users = User::getInactiveUsersByOrganisationDT($admOrg->id, $start, $length, $search);
            if($users)
            {
                $total = 0;
                $output = [];
                foreach($users as $user)
                {
                    $total++;
                    switch($user->user_role)
                    {
                        case 'back':{
                            $user->user_role = 'Admin';
                            break;
                        }
                        case 'front':{
                            $user->user_role = 'WARNING';
                            break;
                        }
                        case 'member':{
                            $user->user_role = 'Member';
                            break;
                        }
                        default:{
                            $user->user_role = 'WARNING';
                        }
                    }
                    $output[] = array(
                        '0' => $user->email,
                        '1' => $user->first_name.' '.$user->last_name,
                        '2' => $user->contact_role,
                        '3' => $user->user_role,
                        '4' => '<div class="btn btn-info resend-invite" data-id="'.$user->id.'" style="margin-right:5px;">Resend</div><div class="btn btn-danger cancel-invite" data-id="'.$user->id.'">Cancel</div>',
                    );
                }

                $results = [];

                $results['data'] = $output;
                $results['recordsTotal'] = $total;
                $results['recordsFiltered'] = $total;

                return \Response::json($results,200);
            }
        }
    }

    public function addNewMember()
    {
        $org = Organisation::getAdminOrganisation();
        if($org)
        {
            $input = \Input::all();
            $validator = \Validator::make($input,
                [
                    'title' => ['required', 'in:Mr,Miss,Mrs'],
                    'first_name' => ['required'],
                    'last_name' => ['required'],
                    'email' => ['required', 'email', 'confirmed'],
                    'contact_tel' => ['numeric'],
                    'fax' => ['numeric'],
                    'user_role' => ['required', 'in:back,member'],
                ]
            );

            if (!$validator->fails())
            {
                $user = User::where('email', $input['email'])->first();
                if(!$user)
                {
                    $inv = UserInvite::getInviteStatusByEmail($input['email']);

                    if($inv)
                    {
                        //INVALID USER INVITE ASSOCIATED
                        $invite = UserInvite::where('email', $input['email'])->first();
                        if($invite)
                        {
                            $invite->delete();
                        }
                    }

                    $user = new User();
                    $user->title = $input['title'];
                    $user->first_name = $input['first_name'];
                    $user->last_name = $input['last_name'];
                    $user->email = $input['email'];
                    $user->user_role = $input['user_role'];

                    if (array_key_exists('middle_name', $input)) {
                        $user->middle_name = $input['middle_name'];
                    }

                    if (array_key_exists('fax', $input)) {
                        $user->fax = $input['fax'];
                    }

                    if (array_key_exists('contact_role', $input)) {
                        $user->contact_role = $input['contact_role'];
                    }

                    if (array_key_exists('contact_tel', $input)) {
                        $user->contact_tel = $input['contact_tel'];
                    }

                    $user->organisation_id = $org->id;
                    $user->activated = false;

                    $pass = bin2hex(openssl_random_pseudo_bytes(8));
                    $user->password = bcrypt($pass);


                    $token = bin2hex(openssl_random_pseudo_bytes(8));
                    $tokenStore = bcrypt($token);

                    if($this->sendInviteToken($token, $input['email']))
                    {
                        $user->save();
                        $invite = new UserInvite();
                        $invite->user_id = $user->id;
                        $invite->token = $tokenStore;
                        $invite->status = 'open';
                        $invite->email_address = $user->email;
                        $invite->save();
                        return \Redirect::to('/admin/members')->with('success', ['success'=>'Successfully sent user invite.']);
                    }
                    else
                    {
                        $error = 'There was an error sending the user invite email. Please try again.';
                        return \Redirect::to('/admin/members')->with('errors_custom', ['FAILURE'=>$error])->withInput();
                    }
                }
                else
                {
                    $error = '';
                    if($user->activated)
                    {
                        $error = 'There is an active user account associated to this email address.';
                    }
                    else
                    {
                        $error = 'There is an inactive user account associated to this email address';
                    }
                    return \Redirect::to('/admin/members')->with('errors_custom', ['FAILURE'=>$error])->withInput();
                }
            }
            else
            {
                $errors = $validator->errors();
                return \Redirect::back()->with('errors', $errors)->withInput();
            }
        }

        return \Redirect::to('/admin/members')->with('errors_custom', ['FAILURE'=>'No Admin Organisation'])->withInput();
    }

    private function sendInviteToken($token, $email)
    {
        try
        {
            \Mail::send('back.emails.auth.UserInvite', array('token'=>$token), function($message) use ($email){
                $message->from('example@example.co.uk', 'Example');
                $message->to($email);
                $message->subject('User Invitation');
            });

            return true;

        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    public function cancelInvitation(){
        if(\Input::has('id'))
        {
            $id = \Input::get('id');
            $user = User::where('id', $id)->where('activated',false)->first();
            if($user)
            {
                $inv = UserInvite::where('user_id', $id)->first();
                if($inv)
                {
                    $inv->delete();
                    $user->delete();

                    return \Response::json('Success',200);
                }
                return \Response::json('User Invite not found.',404);
            }
            return \Response::json('Could not find inactive account with id',404);
        }

        return \Response::json('Required input ID',404);
    }

    public function resendInvitation()
    {
        if(\Input::has('id'))
        {
            $id = \Input::get('id');
            $inv = UserInvite::find($id);
            if($inv)
            {
                $token = bin2hex(openssl_random_pseudo_bytes(8));
                $tokenStore = bcrypt($token);

                $inv->token = $tokenStore;
                if($this->sendInviteToken($token, $inv->email_address))
                {
                    $inv->save();
                    return \Response::json('success',200);
                }
                else
                {
                    return \Response::json('Error sending user invite email please try again.',404);
                }
            }
            else
            {
                return \Response::json('Could not find user invite for this user id.',404);
            }
        }
    }

    public function getClientListForUI($id)
    {
        $user = User::where('id', $id)->where('activated',true)->whereIn('user_role', ['back', 'member'])->first();
        if($user)
        {
            //Get all the organisations minus the admin
            $organisations = Organisation::getOrganisationsNotAdmin();
            $assignedOrgs = Organisation::getOrganisationsByAssigneeId($user->id);

            \Log::info(var_export($organisations,true));
            \Log::info(var_export($assignedOrgs,true));

            $aOIDs = array_map(function($o) { return $o->id; }, $assignedOrgs->toArray());
            $returnArray = [];

            foreach($organisations as $org)
            {
                $temp = [];
                $temp['name'] = $org->name;
                $temp['id'] = $org->id;
                if(in_array($org->id, $aOIDs))
                {
                    $temp['selected'] = 'selected';
                }
                else
                {
                    $temp['selected'] = '';
                }

                $returnArray[] = $temp;
            }
            return \Response::json($returnArray,200);
        }
        return \Response::json('User not found',404);
    }

    public function updateAssignedClients($id)
    {
        $user = User::where('id', $id)->where('activated',true)->whereIn('user_role', ['member'])->first();
        if($user)
        {
            if(\Input::has('ids'))
            {
                $ids = \Input::get('ids');
                $orgIDS = [];
                if(is_array($ids))
                {
                    foreach($ids as $id)
                    {
                        if(is_numeric($id))
                        {
                            $id = intval($id);
                            $orgIDS[] = $id;
                        }
                    }

                    if(Organisation::assignOrganisationsToMember($orgIDS, $user->id))
                    {
                        return \Response::json('success',200);
                    }
                    else
                    {
                        return \Response::json('An error occured',404);
                    }
                }
                return \Response::json('Param ids must be array',404);
            }
            \DB::table('member_organisations')->where('member_id', $id)->delete();
            return \Response::json('success',200);
        }
        return \Response::json('Could not locate user',404);
    }

    public function updateMember($id)
    {
        $user = User::where('id', $id)->where('activated', true)->first();
        if ($user) {
            $input = \Input::all();
            $validator = \Validator::make(
                $input,
                [
                    'title' => ['required', 'in:Mr,Miss,Mrs'],
                    'first_name' => ['required'],
                    'last_name' => ['required'],
                    'contact_tel' => ['numeric'],
                    'fax' => ['numeric'],
                    'user_role' => ['required', 'in:back,member'],
                ]
            );

            if (!$validator->fails()) {
                if (($user->user_role == 'back') && ($input['user_role'] != 'back')) {
                    if (User::getAdminUserCount() <= 1) {
                        return \Redirect::to('/admin/members/edit/' . $id)->with('errors_custom', ['FAILURE' => 'Cannot remove the last remaining admin account.']);
                    }
                }
                $oldUserRole = $user->user_role;

                //HAVE TO PUT THAT ERROR THERE
                $user->title = $input['title'];
                $user->first_name = $input['first_name'];
                $user->last_name = $input['last_name'];
                $user->user_role = $input['user_role'];

                if (array_key_exists('middle_name', $input)) {
                    $user->middle_name = $input['middle_name'];
                }

                if (array_key_exists('fax', $input)) {
                    $user->fax = $input['fax'];
                }

                if (array_key_exists('contact_role', $input)) {
                    $user->contact_role = $input['contact_role'];
                }

                if (array_key_exists('contact_tel', $input)) {
                    $user->contact_tel = $input['contact_tel'];
                }

                $user->save();

                if(($oldUserRole != 'back') && ($user->user_role == 'back'))
                {
                    \DB::table('member_organisations')->where('member_id',$user->id)->delete();
                }

                return \Redirect::to('/admin/members/edit/'.$id)->with('success', ['success'=>'Successfully updated user.']);
            }
            else
            {
                $errors = $validator->errors();
                return \Redirect::to('/admin/members/edit/'.$id)->with('errors', $errors);
            }

        }
        else
        {
            return \Redirect::to('/admin/members/edit/'.$id)->with('errors_custom', ['FAILURE'=>'Could not find member.']);
        }
    }

    public function updateEmail($id)
    {
        $user = User::where('id', $id)->where('activated', true)->first();
        if($user)
        {
            $input = \Input::all();
            $validator = \Validator::make(
                $input,
                [
                    'email'=>['required', 'email', 'unique:users', 'confirmed']
                ]
            );

            if(!$validator->fails())
            {
                $user->email = $input['email'];
                $invite = UserInvite::where('user_id', $id)->first();
                if($invite)
                {
                    $invite->delete();
                }

                $user->save();
                return \Redirect::to('/admin/members/edit/'.$id)->with('success',['Successfully updated user email.']);
            }
            else
            {
                $errors = $validator->errors();
                return \Redirect::to('/admin/members/edit/'.$id)->with('errors', $errors);
            }
        }
        else
        {
            return \Redirect::to('/admin/members/edit/'.$id)->with('errors_custom', ['FAILURE'=>'Could not find user.']);
        }

        return \Redirect::to('/admin/members/edit/'.$id)->with('errors_custom', ['FAILURE'=>'Unexpected Error.']);
    }

    public function deleteUser($id)
    {
        $user = User::where('id', $id)->where('activated', true)->first();
        if($user)
        {
            if(($user->user_role != 'back')||(($user->user_role == 'back') && (User::getAdminUserCount() > 1)))
            {
                \DB::table('member_organisations')->where('member_id', $user->id)->delete();
                $invite = UserInvite::where('user_id', $user->id)->first();
                if($invite)
                {
                    $invite->delete();
                }
                $user->delete();

                return \Response::json('Success', 200);
            }
            else
            {
                return \Response::json('Could not delete final remaining Admin',404);
            }
        }
        else
        {
            return \Response::json('Could not locate user',404);
        }
        return \Response::json('Unforseen error',404);
    }

    public function editAdminOrganisationInfo()
    {
        $input = \Input::all();
        $validator = \Validator::make(
            $input,
            [
                'name'=>['required', 'min:3'],
                'al1'=>['required'],
                'town'=>['required'],
                'region'=>['required'],
                'postcode'=>['required'],
                'country'=>['required'],
                'phone_number'=>['numeric'],
                'fax_number'=>['numeric'],
                'company_type_id'=>['required', 'numeric']
            ]
        );

        if(!$validator->fails())
        {
            $admOrg = Organisation::getAdminOrganisation();
            if($admOrg)
            {
                $admOrg->name = $input['name'];
                $admOrg->al1 = $input['al1'];
                $admOrg->town = $input['town'];
                $admOrg->region = $input['region'];
                $admOrg->postcode = $input['postcode'];
                $admOrg->country = $input['country'];
                $admOrg->company_type_id = $input['company_type_id'];

                //NOW NONE REQUIRED FIELDS
                if(array_key_exists('al2', $input))
                {
                    $admOrg->al2 = $input['al2'];
                }

                if(array_key_exists('phone_number', $input))
                {
                    $admOrg->phone_number = $input['phone_number'];
                }

                if(array_key_exists('fax_number', $input))
                {
                    $admOrg->fax_number = $input['fax_number'];
                }

                if(array_key_exists('vat_number', $input))
                {
                    $admOrg->vat_number = $input['vat_number'];
                }

                if(array_key_exists('company_number', $input))
                {
                    $admOrg->company_number = $input['company_number'];
                }

                $admOrg->save();
                return \Redirect::to('/admin/admin-organisation')->with('success',['success'=>'Successfully updated organisation details.']);

            }
            return \Redirect::to('/admin/admin-organisation')->with('errors_custom',['FAILURE'=>'Could not locate admin organisation']);
        }
        else
        {
            $errors = $validator->errors();
            return \Redirect::to('/admin/admin-organisation')->with('errors', $errors);
        }
        return \Redirect::to('/admin/admin-organisation')->with('errors_custom',['FAILURE'=>'Unexpected Error']);
    }
}