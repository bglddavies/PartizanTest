@extends('back.layout._layout')

@section('title')
    {{{$org->name}}}
@endsection

@section('subtitle')
    Edit User : {{{$editUser->first_name.' '.$editUser->last_name}}}
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
                    <form action="/admin/client-organisations/users/update/post/{{{$editUser->id}}}" method="POST">
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
                        <div class="form-group">
                            <input type="submit" class="btn btn-info" style="width:100%" value="Update Details">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">
                                Quick Links
                            </h3>
                        </div>
                        <div class="box-body quick-links">
                            <a href="/admin/client-organisations/view-users/{{{$org->id}}}" class="btn btn-info">Back to {{{$org->name}}} users</a>
                            <a href="/admin/client-organisations/view/{{{$org->id}}}" class="btn btn-info">Jump to edit organisation</a>
                            <a href="/admin/notification-center/users/{{{$editUser->id}}}" class="btn btn-info">Jump to users notifications</a>
                            <a href="/admin/transfers/users/{{{$editUser->id}}}" class="btn btn-info">Jump to users transfers</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">
                                Client User Relevant Dates
                            </h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date Time</th>
                                    <th>Update</th>
                                </tr>
                                </thead>
                                <tbody id="rDates">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Change Email Address</h3>
                        </div>
                        <div class="box-body">
                            <form action="/admin/client-organisations/users/edit-email/{{{$editUser->id}}}" method="post">
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
    <link rel="stylesheet" href="/plugins/datetimepicker/jquery.datetimepicker.min.css">
    <style>
        .quick-links a{
            margin:10px;
        }
    </style>
@endsection

@section('scripts-bf-dyn')
    <script src="/plugins/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script>
        $(function() {
            $('#delete-user').click(function(){
                if(window.confirm("Really delete this user? This action cannot be undone"))
                {
                    $.ajax({
                        type:'POST',
                        url:'/admin/client-organisations/users/delete/{{{$editUser->id}}}',
                        success:function(response){
                            window.location.replace('/admin/client-organisations/view-users/{{{$org->id}}}');
                        },
                        error:function(response){
                            alert(response.responseText);
                        }
                    });
                }
            });

            getClientRelatedDates();

            function getClientRelatedDates(){
                $.ajax({
                    url:'/admin/client-organisations/users/get-related-dates/{{{$editUser->id}}}',
                    type:'POST',
                    success:function(response){
                        $('#rDates').html('');

                        $(response).each(function(key, value){
                            //I now need to process these with javascript.
                            var outStr = '';
                            outStr += '<tr>';

                            outStr += '<td>';
                            outStr += value.title;
                            outStr += '</td>';

                            outStr += '<td>';
                            outStr += '<input type="text" class="dt-select" value="'+value.dt+'">';
                            outStr += '</td>';

                            outStr += '<td>';
                            outStr += '<div class="btn btn-success save-rdt" data-id="'+value.id+'" >Save</div>';
                            outStr += '</td>';

                            outStr += '</tr>';

                            $('#rDates').append(outStr);

                            $('.dt-select').each(function(){
                                $(this).datetimepicker({
                                    format:'Y-m-d H:i:s'
                                });
                            })
                        });

                    },
                    error:function(response){
                        console.log("ERROR");
                        console.log(response);
                    }
                })
            }

            $('body').on('click', '.save-rdt', function(){
                var row = $(this).closest('tr');
                var dtIn = $(row).find('input');
                var dt = $(dtIn).val();
                var id = $(this).data('id');

                val = $(dtIn).val();
                $.ajax({
                    url:'/admin/client-organisations/users/save-related-date/{{{$editUser->id}}}',
                    type:'POST',
                    data:{
                        rdt_id:id,
                        dt:dt
                    },
                    success:function(response){
                        alert('Updated');
                    },
                    error:function(response){
                        alert('Error. Check log');
                        console.log(response);
                    }
                });

            });
        });
    </script>
@endsection

