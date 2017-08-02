@extends('back.layout._layout')

@section('title')
    My Organisation
@endsection

@section('subtitle')
    Edit Member
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 pull-right">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Member Details</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <form action="/admin/members/update/post/{{{$editUser->id}}}" method="POST">
                        <input type="hidden" name="_token" value="{{{csrf_token()}}}">
                        <div class="form-group @if($errors->has('title')) {{'has-errors'}} @endif" id="title">
                            <label for="title">Title (REQUIRED)</label>
                            <select name="title" class="form-control">
                                <option value="Mr" @if($editUser->title == 'Mr') selected @endif>Mr</option>
                                <option value="Miss" @if($editUser->title == 'Miss') selected @endif>Miss</option>
                                <option value="Mrs" @if($editUser->title == 'Mrs') selected @endif>Mrs</option>
                            </select>
                            @if($errors->has('title')) @foreach($errors->get('title') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('first_name')) {{'has-errors'}} @endif">
                            <label for="first_name">First Name (REQUIRED)</label>
                            <input class="form-control" type="text" name="first_name" placeholder="First Name" value="{{{$editUser->first_name}}}">
                            @if($errors->has('first_name')) @foreach($errors->get('first_name') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('middle_name')) {{'has-errors'}} @endif">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" class="form-control" name="middle_name" placeholder="Middle Name(s)" value="{{{$editUser->middle_name}}}">
                            @if($errors->has('middle_name')) @foreach($errors->get('middle_name') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('last_name')) {{'has-errors'}} @endif">
                            <label for="last_name">Last Name (REQUIRED)</label>
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{{$editUser->last_name}}}">
                            @if($errors->has('last_name')) @foreach($errors->get('last_name') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('contact_role')) {{'has-errors'}} @endif">
                            <label for="contact_role">Job Role</label>
                            <input type="text" class="form-control" name="contact_role" placeholder="Role" value="{{{$editUser->contact_role}}}">
                            @if($errors->has('contact_role')) @foreach($errors->get('contact_role') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('contact_tel')) {{'has-errors'}} @endif">
                            <label for="contact_tel">Telephone Number</label>
                            <input type="text" class="form-control" name="contact_tel" placeholder="Telephone Number" value="{{{$editUser->contact_tel}}}">
                            @if($errors->has('contact_tel')) @foreach($errors->get('contact_tel') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('fax')) {{'has-errors'}} @endif">
                            <label for="fax">Fax Number</label>
                            <input type="text" class="form-control" name="fax" placeholder="Fax Number" value="{{{$editUser->fax}}}">
                            @if($errors->has('fax')) @foreach($errors->get('fax') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('user_role')) {{'has-errors'}} @endif">
                            <label for="user_role">User Role (REQUIRED)</label>
                            <select name="user_role" id="user-role" class="form-control">
                                <option value="member" @if($editUser->user_role == 'member') selected @endif>Member</option>
                                <option value="back" @if($editUser->user_role == 'back') selected @endif>Admin</option>
                            </select>
                            @if($errors->has('user_role')) @foreach($errors->get('user_role') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info" style="width:100%" value="Update Details">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if($editUser->user_role == 'member')
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Assigned Clients</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-success btn-sm" id="save-clients"><i class="fa fa-floppy-o fa-2x"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <select id="member-clients" multiple=multiple style="margin: 20px;width:100%;"></select>
                        </div>
                        <div class="col-xs-12">
                            <h4 style="margin-top:20px;">Current Clients</h4>
                            <div id="client-list">
                                There are no current clients to show.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @else
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Assigned Clients</h3>
                </div>
                <div class="box-body">
                    Admins are assigned all clients. This is to allow administration over them even if these clients are not assigned to an organisation member.
                </div>
            </div>
        </div>
        @endif
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Change Email Address</h3>
                        </div>
                        <div class="box-body">
                            <form action="/admin/members/edit-email/{{{$editUser->id}}}" method="post">
                                <input type="hidden" name="_token" value="{{{csrf_token()}}}">
                                <div class="form-group @if($errors->has('email')) {{'has-errors'}} @endif">
                                    <label for="email">Email Address (REQUIRED)</label>
                                    <input type="text" class="form-control" name="email" placeholder="Email Address" value="{{{$editUser->email}}}">
                                    @if($errors->has('email')) @foreach($errors->get('email') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                                </div>
                                <div class="form-group @if($errors->has('email_confirmation')) {{'has-errors'}} @endif">
                                    <label for="email_confirmation">Confirm Email (REQUIRED)</label>
                                    <input type="text" class="form-control" name="email_confirmation" placeholder="Confirm Email Address">
                                    @if($errors->has('email_confirmation')) @foreach($errors->get('email_confirmation') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-info" style="width:100%" value="Update Email">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box collapsed-box">
                        <div class="box-header">
                            <h3 class="box-title">Delete User</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="box-body" style="display:none;">
                            <div class="btn btn-danger" id="delete-user">DELETE USER</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles-bf-dyn')
    <link rel="stylesheet" href="/plugins/paramqueryselect/pqselect.min.css">
    <style>
        #member-clients{
            display:none;
        }
        .pq-select-popup .ui-widget-header{
            background-color:#605ca8;
            color:#fff;
        }

        .pq-select-popup .pq-select-menu{
            background-color:#ddd;
        }

        .pq-select-button{
            width:100% !important;
            background-color:#605ca8;
            color:#fff;
        }

        .pq-select-button .pq-select-text{
            padding: 15px;
            font-size: 16px;
        }

        .selected-organisation{
            float:left;
            width:100%;
            padding:10px;
            border-bottom:1px solid #ddd;
        }

        .selected-organisation .name{
            float:left;
            padding:5px;
        }
        .selected-organisation .btn{
            float:right;
        }

        .selected-organisation.new{
            color:green;
        }
        .selected-organisation.deleted{
            color:red;
        }
    </style>
@endsection

@section('scripts-bf-dyn')
    <script src="/plugins/paramqueryselect/pqselect.min.js"></script>
    <script>
        $(function() {
            @if($editUser->user_role == "member")
            $.ajax({
               type:'post',
                url:'/admin/members/get-clients/{{{$editUser->id}}}',
                success:function(response){
                    $('#client-list').html('');
                    $(response).each(function(index, element){
                        var bString = '<option value="'+ element['id'] +'" '+element['selected']+'>'+element['name']+'</option>';
                        $('#member-clients').append(bString);
                        if(element['selected'] == 'selected')
                        {
                            var bString = '<div class="selected-organisation existing" data-id="'+element['id']+'"><div class="name">'+element['name']+'</div><a class="btn btn-info" href="/admin/clients/edit/'+element['id']+'">Edit</a></div>';
                            $('#client-list').append(bString);
                        }
                    });

                    $("#member-clients").pqSelect({
                        multiplePlaceholder: 'Assign clients<i class="fa fa-chevron-down" style="float:right; padding-top:2px;"></i>',
                        maxDisplay:0,
                        checkbox: true, //adds checkbox to options
                        displayText: 'Assign Clients<i class="fa fa-chevron-down" style="float:right; padding-top:2px; padding-right:2px;"></i>'
                    }).on("change", function(evt) {
                        var val = $(this).val();
                        //Check for deleting ones
                        //Check for new ones
                        var exIDArray = [];
                        $('.selected-organisation.existing').each(function(index, value){
                            var tID = $(this).data('id');
                            tID = tID.toString();
                            exIDArray.push(tID);

                            if($.inArray(tID, val) >= 0)
                            {
                                //unchanged
                                if(!$(this).hasClass('unchaged'))
                                {
                                    $(this).addClass('unchanged');
                                }

                                if($(this).hasClass('deleted'))
                                {
                                    $(this).removeClass('deleted');
                                }
                            }
                            else
                            {
                                //deleted
                                if(!$(this).hasClass('deleted'))
                                {
                                    $(this).addClass('deleted');
                                }
                            }

                        });
                        $('.selected-organisation.new').each(function(index, value){
                            var tID = $(this).data('id');
                            tID = tID.toString();

                            if($.inArray(tID, val) < 0)
                            {
                                //no longer added
                                $(this).remove();
                            }

                        });

                        //Now, I need to get the ID's of all the new elemens
                        //Any ID's in val that arent in exIDArray
                        var diff = $(val).not(exIDArray).get();

                        $(diff).each(function(index, value){
                            //CHECK FOR NEW ALREADY ADDED
                            if($('.selected-organisation.new[data-id="'+value+'"]').length == 0)
                            {
                                var newOrg = $('#member-clients option[value="'+value+'"]');
                                var name = $(newOrg).text();
                                var bString = '<div class="selected-organisation new" data-id="'+value+'"><div class="name">'+name+'</div><a class="btn btn-info" href="/admin/clients/edit/'+value+'">Edit</a></div>';
                                $('#client-list').append(bString);
                            }
                        });
                    });
                },
                error:function(response){
                    //Alert of error
                }
            });

            $('#save-clients').click(function(){
                var selected = $('#member-clients option:selected');
                var idArray = [];

                $(selected).each(function(index, element){
                   idArray.push($(this).val());
                });

                if(window.confirm('Are you sure you want to update the assigned clients for this member?'))
                {
                    console.log(idArray);
                    $.ajax({
                        type:'POST',
                        url:'/admin/members/update-assigned/{{{$editUser->id}}}',
                        data:{
                            ids:idArray
                        },
                        success:function(response)
                        {
                            window.location.reload();
                        },
                        error:function(response)
                        {

                        }
                    });
                }
            });
            @endif

            $('#delete-user').click(function(){
               if(window.confirm("Really delete this user? This action cannot be undone"))
               {
                   $.ajax({
                       type:'POST',
                       url:'/admin/members/delete/{{{$editUser->id}}}',
                       success:function(response){
                           window.location.replace('/admin/members');
                       },
                       error:function(response){
                           alert(response.responseText);
                       }
                   });
               }
            });
        });
    </script>
@endsection

