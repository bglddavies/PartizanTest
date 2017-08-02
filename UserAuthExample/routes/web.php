<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'HomepageController@index');

Route::group(['namespace' => 'Auth', 'prefix'=>'user'], function(){
    Route::get('/reset-password', 'UserAuthController@passwordResetStartRoute');
    Route::get('/reset-password-2auth/{token}', 'UserAuthController@passwordResetSecondAuth');
    Route::get('/logout', 'UserAuthController@logout');
    Route::get('/activate/{token}', 'UserAuthController@activateIndex');

    Route::post('/login', 'UserAuthController@loginRoute');
    Route::post('/request-reset', 'UserAuthController@passwordResetFirstAuth');
    Route::post('/request-reset-firstauth', 'UserAuthController@passwordResetFirstAuthSubmit');
    Route::post('/reset-password-2auth-submit', 'UserAuthController@passwordResetSecondAuthSubmit');
    Route::post('/change-password', 'UserAuthController@passwordChangeFormSubmit');
    Route::post('/activate/post', 'UserAuthController@activate');
    //Add the user activate route
});

//PROTECTED ADMIN ROUTE GROUP
Route::group(['prefix'=>'admin', 'middleware'=>'checkadmin', 'namespace'=>'Admin'], function(){
    Route::get('/', 'AdminPageController@index');

    //ADMIN ORGANISATION MEMBER ROUTES
    Route::get('/members/edit/{id}', 'AdminPageController@membersEdit')->where('id', '[0-9]+');
    Route::get('/members', 'AdminPageController@members');

    Route::post('/members/active', 'AdminOrganisationController@getActiveAdminOrganisationMembersDT');
    Route::post('/members/inactive', 'AdminOrganisationController@getInactiveAdminOrganisationMembersDT');
    Route::post('/members/add', 'AdminOrganisationController@addNewMember');
    Route::post('/members/cancel-invite', 'AdminOrganisationController@cancelInvitation');
    Route::post('/members/resend-invite', 'AdminOrganisationController@resendInvitation');

    Route::post('/members/get-clients/{id}', 'AdminOrganisationController@getClientListForUI')->where('id', '[0-9]+');
    Route::post('/members/update-assigned/{id}', 'AdminOrganisationController@updateAssignedClients')->where('id', '[0-9]+');
    Route::post('/members/update/post/{id}', 'AdminOrganisationController@updateMember')->where('id', '[0-9]+');
    Route::post('/members/edit-email/{id}', 'AdminOrganisationController@updateEmail')->where('id', '[0-9]+');
    Route::post('/members/delete/{id}', 'AdminOrganisationController@deleteUser')->where('id', '[0-9]+');

    //ADMIN ORGANISATION ROUTES
    Route::get('/admin-organisation', 'AdminPageController@editAdminOrganisation');

    Route::post('/admin-organisation/edit-details', 'AdminOrganisationController@editAdminOrganisationInfo');

    //CLIENT ORGANISATIONS ROUTES
    Route::get('/client-organisations', 'AdminPageController@clientOrganisations');
    Route::get('/client-organisations/view-users/{id}', 'AdminPageController@clientOrganisationUsers')->where('id', '[0-9]+');
    Route::get('/client-organisations/users/edit/{id}', 'AdminPageController@clientOrganisationEditUser')->where('id', '[0-9]+');
    Route::get('/client-organisations/view/{id}', 'AdminPageController@viewClientOrganisation')->where('id', '[0-9]+');

    Route::post('/client-organisations/add', 'AdminClientOrganisationController@addClientOrganisation');
    Route::post('/client-organisations/get-clients-dt', 'AdminClientOrganisationController@getClientOrganisationsDT');
    Route::post('/client-organisations/users/add/{id}', 'AdminClientOrganisationController@addClientOrganisationUser')->where('id', '[0-9]+');
    Route::post('/client-organisations/users/get-active/{id}', 'AdminClientOrganisationController@getActiveClientUsers')->where('id', '[0-9]+');
    Route::post('/client-organisations/users/get-inactive/{id}', 'AdminClientOrganisationController@getInactiveClientUsers')->where('id', '[0-9]+');
    Route::post('/client-organisations/users/cancel-invite', 'AdminClientOrganisationController@cancelInvitation');
    Route::post('/client-organisations/users/resend-invite', 'AdminClientOrganisationController@resendInvitation');
    Route::post('/client-organisations/users/edit-email/{id}', 'AdminClientOrganisationController@updateUserEmail')->where('id', '[0-9]+');
    Route::post('/client-organisations/users/update/post/{id}', 'AdminClientOrganisationController@updateUserInfo')->where('id', '[0-9]+');
    Route::post('/client-organisations/users/delete/{id}', 'AdminClientOrganisationController@deleteUser')->where('id', '[0-9]+');
    Route::post('/client-organisations/users/get-related-dates/{id}', 'AdminClientOrganisationController@getUserRelatedDates')->where('id', '[0-9]+');
    Route::post('/client-organisations/users/save-related-date/{id}', 'AdminClientOrganisationController@saveUserRelatedDate')->where('id', '[0-9]+');
    Route::post('/client-organisations/view/edit/{id}', 'AdminClientOrganisationController@updateOrganisationDetails')->where('id', '[0-9]+');
    Route::post('/client-organisations/view/get-related-dates/{id}', 'AdminClientOrganisationController@getOrganisationRelatedDates')->where('id', '[0-9]+');
    Route::post('/client-organisations/view/save-related-date/{id}', 'AdminClientOrganisationController@updateOrganisationRelatedDate')->where('id', '[0-9]+');
    Route::post('/client-organisations/view/delete/{id}', 'AdminClientOrganisationController@deleteOrganisation')->where('id', '[0-9]+');


    //CONFIG ROUTES
    Route::get('/config', 'AdminPageController@getConfiguration');

    Route::post('/config/getCompanyTypes', 'AdminConfigController@getCompanyTypes');
    Route::post('/config/update-ct/{id}', 'AdminConfigController@updateCompanyType')->where('id', '[0-9]+');
    Route::post('/config/delete-ct/{id}', 'AdminConfigController@deleteCompanyType')->where('id','[0-9]+');
    Route::post('/config/add-ct', 'AdminConfigController@addCompanyType');
});


//PROTECTED USER ROUTE GROUP
Route::group(['prefix'=>'dashboard', 'middleware'=>'checkuser'],function(){
    Route::get('/',function(){
        return \Response::json('User');
    });
});
