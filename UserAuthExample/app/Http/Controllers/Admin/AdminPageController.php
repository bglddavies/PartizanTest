<?php

namespace App\Http\Controllers\Admin;
/**
 * Class AdminPageController
 * @package App\Http\Controllers\Admin
 * @description Controls Admin Dashboard page. Controls no functionality beyond this. For functionality options check respective classes in this namespace
 *
 */

use App\Models\RelevantDatetime;
use App\Models\User;
use App\Models\Organisation;
use App\Models\OrganisationType;
use App\Models\DocumentTransferTemplate;
use App\Models\DocumentCategory;

class AdminPageController extends \App\Http\Controllers\Controller
{

    public function index()
    {
        \View::share('menu', 'dashboard');
        return \View::make('back.pages.dashboard');
    }

    public function members()
    {
        \View::share('menu', 'adm_org-members');
        return \View::make('back.pages.members.list');
    }

    public function membersEdit($id)
    {
        $user = User::where('id', $id)->where('activated', true)->first();
        if($user)
        {
            \View::share('menu', 'adm_org-members');
            \View::share('editUser', $user);
            return \View::make('back.pages.members.edit');
        }
        return \Redirect::to('/admin/members')->with('errors_custom', ['ERROR'=>'Active user with this ID could not be found.']);
    }

    public function editAdminOrganisation()
    {
        $admOrg = Organisation::getAdminOrganisation();
        $orgTypes = OrganisationType::getAllTypes();

        \View::share('admOrg', $admOrg);
        \View::share('types', $orgTypes);

        \View::share('menu', 'adm_org-details');
        return \View::make('back.pages.admin_organisation.edit');
    }

    public function clientOrganisations()
    {
        \View::share('menu', 'client-organisations');
        $orgTypes = OrganisationType::getAllTypes();
        \View::share('types', $orgTypes);
        return \View::make('back.pages.client_organisations.list');
    }

    public function clientOrganisationEditUser($id)
    {
        $admOrg = Organisation::getAdminOrganisation();
        if($admOrg)
        {
            if($admOrg->id != $id)
            {
                $user = User::where('id', $id)->first();
                if($user)
                {
                    $org = Organisation::where('id', $user->organisation_id)->first();
                    if($org)
                    {
                        \View::share('menu', 'client-organisations');
                        \View::share('editUser',$user);
                        \View::share('org', $org);
                        return \View::make('back.pages.client_organisations.user_edit');
                    }
                    return \Redirect::to('/admin/client-organisations')->with('errors_custom', ['FAILURE'=>'Could not find the users organisation.']);
                }
                return \Redirect::to('/admin/client-organisations')->with('errors_custom', ['FAILURE'=>'Could not find user.']);
            }
            return \Redirect::to('/admin/members')->with('errors_custom',['FAILURE'=>'You can only edit admin organisation members from here.']);
        }
        return \Redirect::to('/')->with('errors_custom', ['FAILURE'=>'Admin Organisation is not yet configured.']);
    }

    public function clientOrganisationUsers($id)
    {
        \View::share('menu', 'client-organisations');
        $admOrg = Organisation::getAdminOrganisation();
        if($admOrg->id != $id)
        {
            $org = Organisation::where('id', $id)->first();
            if($org)
            {
                \View::share('org', $org);
                return \View::make('back.pages.client_organisations.users');
            }
            return \Redirect::to('/admin/client-organisations')->with('errors_custom', ['FAILURE'=>'Could not locate organisation']);
        }
        return \Redirect::to('/admin/admin-organisation');
    }

    public function getConfiguration()
    {
        \View::share('menu', 'configuration');
        return \View::make('back.pages.configuration.index');
    }

    public function viewClientOrganisation($id)
    {
        \View::share('menu', 'client-organisations');
        $admOrg = Organisation::getAdminOrganisation();
        if($admOrg->id != $id)
        {
            $org = Organisation::where('id', $id)->first();
            if($org)
            {
                \View::share('org', $org);
                $orgTypes = OrganisationType::getAllTypes();
                \View::share('types', $orgTypes);
                return \View::make('back.pages.client_organisations.edit');
            }
            return \Redirect::to('/admin/client-organisations')->with('errors_custom', ['FAILURE'=>'Could not locate organisation']);
        }
        return \Redirect::to('/admin/admin-organisation');
    }
}
