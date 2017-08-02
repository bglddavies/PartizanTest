<?php
/**
 * Created by PhpStorm.
 * User: baglad
 * Date: 25/01/2017
 * Time: 11:06
 */

namespace app\Http\Controllers\Admin;

use App\Models\Organisation;
use App\Models\OrganisationType;
use App\Models\User;
use App\Models\UserInvite;
use App\Models\ClientRelatedDatetime;
use App\Models\OrganisationRelatedDatetime;

class AdminClientOrganisationController extends \App\Http\Controllers\Controller
{
    public function addClientOrganisation()
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
                'company_number'=>['numeric'],
                'phone_number'=>['numeric'],
                'fax_number'=>['numeric'],
                'company_type_id'=>['required', 'numeric']
            ]
        );

        if(!$validator->fails())
        {
            $org = new Organisation();
            $org->name = $input['name'];
            $org->al1 = $input['al1'];
            $org->town = $input['town'];
            $org->region = $input['region'];
            $org->postcode = $input['postcode'];
            $org->country = $input['country'];
            $org->company_type_id = $input['company_type_id'];

            //NOW NONE REQUIRED FIELDS
            if(array_key_exists('al2', $input))
            {
                $org->al2 = $input['al2'];
            }

            if(array_key_exists('phone_number', $input))
            {
                $org->phone_number = $input['phone_number'];
            }

            if(array_key_exists('fax_number', $input))
            {
                $org->fax_number = $input['fax_number'];
            }

            if(array_key_exists('vat_number', $input))
            {
                $org->vat_number = $input['vat_number'];
            }

            if(array_key_exists('company_number', $input))
            {
                $org->company_number = $input['company_number'];
            }

            $org->save();
            return \Redirect::to('/admin/client-organisations')->with('success',['success'=>'Successfully updated organisation details.']);
        }
        $errors = $validator->errors();
        return \Redirect::to('/admin/client-organisations')->with('errors', $errors);
    }

    public function getClientOrganisationsDT()
    {
        $length = 10;
        $start = 0;
        $search = '';

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

        $output = [];
        $total = 0;


        $organisations = Organisation::getOrganisationsNotAdminDT($start, $length, $search);
        $types = OrganisationType::getAllTypes();

        foreach($organisations as $org)
        {

            $users = User::getActiveUsersByOrganisation($org->id);
            $uTTL = count($users);
            $total++;
            $temp = array();
            $tString = 'Undefined';
            $temp['0'] = $org->name;
            $temp['1'] = $org->vat_number;
            $temp['2'] = $org->company_number;
            $temp['3'] = $org->phone_number;
            $temp['4'] = $uTTL;

            $temp['5'] = '<a class="btn btn-success" href="/admin/client-organisations/view/'.$org->id.'">View</a>&nbsp;<a class="btn btn-info" href="/admin/client-organisations/view-users/'.$org->id.'">View Users</a>';
            $output[] = $temp;
        }

        $results = [];

        $results['data'] = $output;
        $results['recordsTotal'] = $total;
        $results['recordsFiltered'] = $total;

        return \Response::json($results,200);
    }

    public function addClientOrganisationUser($id)
    {
        \Log::info('1');
        $admOrg = Organisation::getAdminOrganisation();
        if($admOrg)
        {
            if($admOrg->id != $id) {
                $org = Organisation::where('id', $id)->first();
                if($org)
                {
                    $input = \Input::all();
                    $validator = \Validator::make(
                        $input,
                        [
                            'title' => ['required', 'in:Mr,Miss,Mrs'],
                            'first_name' => ['required'],
                            'last_name' => ['required'],
                            'email' => ['required', 'email', 'confirmed', 'unique:users'],
                            'contact_tel' => ['numeric'],
                            'fax' => ['numeric'],
                        ]
                    );

                    if (!$validator->fails()) {
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

                        $newUser = new User();

                        $newUser->title = $input['title'];
                        $newUser->first_name = $input['first_name'];
                        $newUser->last_name = $input['last_name'];
                        $newUser->email = $input['email'];
                        $newUser->user_role = 'front';

                        if (array_key_exists('middle_name', $input)) {
                            $newUser->middle_name = $input['middle_name'];
                        }

                        if (array_key_exists('fax', $input)) {
                            $newUser->fax = $input['fax'];
                        }

                        if (array_key_exists('contact_tel', $input)) {
                            $newUser->contact_tel = $input['contact_tel'];
                        }

                        if (array_key_exists('contact_role', $input)) {
                            $newUser->contact_role = $input['contact_role'];
                        }

                        $newUser->organisation_id = $org->id;
                        $newUser->activated = false;

                        $pass = bin2hex(openssl_random_pseudo_bytes(8));
                        $newUser->password = bcrypt($pass);

                        $token = bin2hex(openssl_random_pseudo_bytes(8));
                        $tokenStore = bcrypt($token);
                        \Log::info('2');
                        if($this->sendInviteToken($token, $input['email']))
                        {
                            \Log::info('I GOT HERE');
                            $newUser->save();
                            $invite = new UserInvite();
                            $invite->user_id = $newUser->id;
                            $invite->token = $tokenStore;
                            $invite->status = 'open';
                            $invite->email_address = $newUser->email;
                            $invite->save();
                            return \Redirect::to('/admin/client-organisations/view-users/'.$id)->with('success', ['success'=>'Successfully sent user invite.']);
                        }
                    }

                    $errors = $validator->errors();
                    return \Redirect::to('/admin/client-organisations/view-users/'.$id)->with('errors', $errors)->withInput();
                }
                return \Redirect::to('/admin/client-organisations')->with('errors_custom', ['FAILURE'=>'Could not locate this organisation']);
            }

            return \Redirect::to('/admin/members')->with('errors_custom', ['FAILURE'=>'Cannot add Admin Organisation Members this way.']);
        }
        return \Redirect::to('/admin/client-organisations/view-users/'.$id)->with('errors_custom', ['FAILURE'=>'Could not compare with admin organisation. Configuration incorrect.']);
    }

    private function sendInviteToken($token, $email)
    {
        try
        {
            \Mail::send('back.emails.auth.UserInvite', array('token'=>$token), function($message) use ($email){
                $message->from('birkystomper99@gmail.com', 'Byron');
                $message->to($email);
                $message->subject('User Invitation');
            });

            return true;

        }
        catch(\Exception $e)
        {
            \Log::info($e->getTraceAsString());
            return false;
        }
    }

    public function getActiveClientUsers($id)
    {
        $admOrg = Organisation::getAdminOrganisation();
        if($admOrg)
        {
            if($admOrg->id != $id)
            {
                $org = Organisation::where('id', $id)->first();
                if($org)
                {
                    $length = 10;
                    $start = 0;
                    $search = '';

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

                    $output = [];
                    $total = 0;

                    $users = User::getActiveUsersByOrganisationDT($id, $start, $length, $search);

                    foreach($users as $user)
                    {
                        $total++;
                        $temp = [];

                        $temp['0'] = $user->email;
                        $temp['1'] = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
                        $temp['2'] = $user->contact_role;
                        $temp['3'] = '<a class="btn btn-success" href="/admin/client-organisations/users/edit/'.$user->id.'">View</a>';

                        $output[] = $temp;
                    }

                    $results = [];

                    $results['data'] = $output;
                    $results['recordsTotal'] = $total;
                    $results['recordsFiltered'] = $total;

                    return \Response::json($results,200);
                }
                //ORGANISATION NOT FOUND
                return \Redirect::to('/admin/client-organisations')->with('errors_custom', ['FAILURE'=>'Cannot find organisation']);
            }
            //ORGANISATION IS ADMIN
            return \Redirect::to('/admin/members')->with('errors_custom', ['FAILURE'=>'Cannot view admin members from client organisations']);
        }
        //ADMIN ORG NOT CONFIGURED
        return \Redirect::to('/admin')->with('errors_custom', ['FAILURE'=>'Admin Organisation has not been configured.']);
    }

    public function getInactiveClientUsers($id)
    {
        $admOrg = Organisation::getAdminOrganisation();
        if($admOrg)
        {
            if($admOrg->id != $id)
            {
                $org = Organisation::where('id', $id)->first();
                if($org)
                {
                    $length = 10;
                    $start = 0;
                    $search = '';

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

                    $output = [];
                    $total = 0;

                    $users = User::getInactiveUsersByOrganisationDT($id, $start, $length, $search);

                    foreach($users as $user)
                    {
                        $total++;
                        $temp = [];

                        $temp['0'] = $user->email;
                        $temp['1'] = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
                        $temp['2'] = $user->contact_role;
                        $temp['3'] = '<div class="btn btn-info resend-invite" data-id="'.$user->id.'" style="margin-right:5px;">Resend</div><div class="btn btn-danger cancel-invite" data-id="'.$user->id.'">Cancel</div>';

                        $output[] = $temp;
                    }

                    $results = [];

                    $results['data'] = $output;
                    $results['recordsTotal'] = $total;
                    $results['recordsFiltered'] = $total;

                    return \Response::json($results,200);
                }
                //ORGANISATION NOT FOUND
                return \Redirect::to('/admin/client-organisations')->with('errors_custom', ['FAILURE'=>'Cannot find organisation']);
            }
            //ORGANISATION IS ADMIN
            return \Redirect::to('/admin/members')->with('errors_custom', ['FAILURE'=>'Cannot view admin members from client organisations']);
        }
        //ADMIN ORG NOT CONFIGURED
        return \Redirect::to('/admin')->with('errors_custom', ['FAILURE'=>'Admin Organisation has not been configured.']);
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

    public function updateUserEmail($id)
    {
        $user = User::where('id', $id)->where('activated', true)->first();
        if($user)
        {
            $admOrg = Organisation::getAdminOrganisation();
            if($admOrg->id != $user->organisation_id)
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
                    return \Redirect::to('/admin/client-organisations/users/edit/'.$id)->with('success',['Successfully updated user email.']);
                }
                else
                {
                    $errors = $validator->errors();
                    return \Redirect::to('/admin/client-organisations/users/edit/'.$id)->with('errors', $errors);
                }
            }
            else
            {
                return \Redirect::to('/admin/members/edit/'.$id)->with('errors_custom', ['FAILURE'=>'Cannot edit user from here.']);
            }
        }
        else
        {
            return \Redirect::to('/admin/client-organisations')->with('errors_custom', ['FAILURE'=>'Could not find user.']);
        }

        return \Redirect::to('/admin/client-organisations')->with('errors_custom', ['FAILURE'=>'Unexpected Error.']);
    }

    public function updateUserInfo($id)
    {
        $user = User::where('id', $id)->where('activated', true)->first();
        if ($user)
        {
            $admOrg = Organisation::getAdminOrganisation();
            if($user->organisation_id != $admOrg->id)
            {
                $input = \Input::all();
                $validator = \Validator::make(
                    $input,
                    [
                        'title' => ['required', 'in:Mr,Miss,Mrs'],
                        'first_name' => ['required'],
                        'last_name' => ['required'],
                        'contact_tel' => ['numeric'],
                        'fax' => ['numeric'],
                    ]
                );

                if (!$validator->fails())
                {
                    $user->title = $input['title'];
                    $user->first_name = $input['first_name'];
                    $user->last_name = $input['last_name'];

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

                    return \Redirect::to('/admin/client-organisations/users/edit/' . $id)->with('success', ['success' => 'Successfully updated user.']);
                }
                else
                {
                    $errors = $validator->errors();
                    return \Redirect::to('/admin/client-organisations/users/edit/' . $id)->with('errors', $errors);
                }
            }
            return \Redirect::to('/admin/members')->with('errors_custom', ['FAILURE'=>'Cannot edit this user in this way']);

        }
        return \Redirect::to('/admin/client-organisations')->with('errors_custom', ['FAILURE'=>'Could not locate user']);
    }

    public function deleteUser($id)
    {
        $user = User::where('id', $id)->where('activated', true)->first();
        if($user)
        {
            $admOrg = Organisation::getAdminOrganisation();
            if($admOrg->id != $user->organisation_id)
            {

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
                return \Response::json('Cannot delete user this way', 404);
            }
        }
        else
        {
            return \Response::json('Could not locate user',404);
        }
        return \Response::json('Unforseen error',404);
    }

    public function getUserRelatedDates($id)
    {
        $user = User::where('id',$id)->where('activated',true)->first();
        if($user)
        {
            $crdt = ClientRelatedDatetime::getCRDTByID($id);
            return \Response::json($crdt,200);
        }

        return \Response::json("Cannot locate user",404);
    }

    public function saveUserRelatedDate($id)
    {
        $user = User::where('id', $id)->where('activated', true)->first();

        if($user)
        {
            if($user->user_role == 'front')
            {
                $input = \Input::all();

                $validator = \Validator::make(
                    $input,
                    [
                        'rdt_id'=>['required', 'numeric'],
                        'dt'=>['date_format:Y-m-d H:i:s']
                    ]
                );

                if(!$validator->fails())
                {
                    if((array_key_exists('dt', $input))&&($input['dt'] != ''))
                    {
                        ClientRelatedDatetime::updateCRDTByID($id, $input['rdt_id'], $input['dt']);
                    }
                    else
                    {
                        ClientRelatedDatetime::where('rdt_id', $input['rdt_id'])->where('user_id', $id)->delete();
                    }

                    return \Response::json('success',200);
                }

                $errors = $validator->errors();

                return \Response::json($errors,404);

            }
            return \Response::json('Error, cannot add related dates to this user type.',404);
        }
        return \Response::json('Error, cannot find user',404);
    }

    public function updateOrganisationDetails($id)
    {
        \Log::info("Got here");
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
            $org = Organisation::where('id', $id)->first();
            if($org)
            {
                $org->name = $input['name'];
                $org->al1 = $input['al1'];
                $org->town = $input['town'];
                $org->region = $input['region'];
                $org->postcode = $input['postcode'];
                $org->country = $input['country'];
                $org->company_type_id = $input['company_type_id'];

                //NOW NONE REQUIRED FIELDS
                if(array_key_exists('al2', $input))
                {
                    $org->al2 = $input['al2'];
                }

                if(array_key_exists('phone_number', $input))
                {
                    $org->phone_number = $input['phone_number'];
                }

                if(array_key_exists('fax_number', $input))
                {
                    $org->fax_number = $input['fax_number'];
                }

                if(array_key_exists('vat_number', $input))
                {
                    $org->vat_number = $input['vat_number'];
                }

                if(array_key_exists('company_number', $input))
                {
                    $org->company_number = $input['company_number'];
                }

                $org->save();

                return \Redirect::to('/admin/client-organisations/view/'.$id)->with('success',['success'=>'Successfully updated organisation details.']);

            }
            return \Redirect::to('/admin/client-organisations/')->with('errors_custom',['FAILURE'=>'Could not locate organisation']);
        }
        else
        {
            $errors = $validator->errors();
            return \Redirect::to('/admin/client-organisations/view/'.$id)->with('errors', $errors);
        }
        return \Redirect::to('/admin/client-organisations/view/'.$id)->with('errors_custom',['FAILURE'=>'Unexpected Error']);
    }

    public function getOrganisationRelatedDates($id)
    {
        $org = Organisation::where('id',$id)->first();
        if($org)
        {
            $ordt = OrganisationRelatedDatetime::getORDTByID($id);
            return \Response::json($ordt,200);
        }

        return \Response::json("Cannot locate organisation",404);
    }

    public function updateOrganisationRelatedDate($id)
    {
        $org = Organisation::where('id', $id)->first();

        if($org)
        {
            $admOrg = Organisation::getAdminOrganisation();
            if($admOrg->id != $id)
            {
                $input = \Input::all();

                $validator = \Validator::make(
                    $input,
                    [
                        'rdt_id'=>['required','numeric'],
                        'dt'=>['date_format:Y-m-d H:i:s']
                    ]
                );

                if(!$validator->fails())
                {
                    if((array_key_exists('dt', $input)) && ($input['dt'] != ''))
                    {
                        OrganisationRelatedDatetime::updateORDTByID($id, $input['rdt_id'], $input['dt']);
                    }
                    else
                    {
                        OrganisationRelatedDatetime::where('rdt_id', $input['rdt_id'])->where('organisation_id', $id)->delete();
                    }
                    return \Response::json('success',200);
                }

                $errors = $validator->errors();

                return \Response::json($errors,404);

            }
            return \Response::json('Error, cannot add related dates to this organisation.',404);
        }
        return \Response::json('Error, cannot find organisation',404);
    }

    public function deleteOrganisation($id)
    {
        $admOrg = Organisation::getAdminOrganisation();
        if($admOrg->id != $id)
        {
            $org = Organisation::where('id', $id)->first();
            if($org)
            {
                User::deleteFrontUsersByOrganisationID($id);
                Organisation::deleteOrganisationDataByOrganisationID($id);
                $org->delete();

                return \Response::json('success',200);
            }
            return \Response::json('Could not locate organisation',404);
        }

        return \Response::json('Cannot delete admin organisation like this',404);
    }
}