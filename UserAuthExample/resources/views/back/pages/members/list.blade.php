@extends('back.layout._layout')

@section('title')
    My Organisation
@endsection

@section('subtitle')
    Members
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4 col-xs-12 pull-right">
            <div class="box @if(count($errors)==0) collapsed-box @endif">
                <div class="box-header">
                    <h3 class="box-title">Add New Member</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa @if(count($errors)==0) fa-plus @else fa-minus @endif"></i></button>
                    </div>
                </div>
                <div class="box-body" style="display:@if(count($errors)==0) none @else block @endif;">
                    <form action="/admin/members/add" method="post">
                        <input type="hidden" name="_token" value="{{{csrf_token()}}}">
                        <div class="form-group @if($errors->has('title')) {{'has-errors'}} @endif" id="title">
                            <label for="title">Title (REQUIRED)</label>
                            <select name="title" class="form-control">
                                <option value="Mr" @if(Input::old('title') == 'Mr') selected @endif>Mr</option>
                                <option value="Miss" @if(Input::old('title') == 'Miss') selected @endif>Miss</option>
                                <option value="Mrs" @if(Input::old('title') == 'Mrs') selected @endif>Mrs</option>
                            </select>
                            @if($errors->has('title')) @foreach($errors->get('title') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('first_name')) {{'has-errors'}} @endif">
                            <label for="first_name">First Name (REQUIRED)</label>
                            <input class="form-control" type="text" name="first_name" placeholder="First Name" value="{{Input::old('first_name')}}">
                            @if($errors->has('first_name')) @foreach($errors->get('first_name') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('middle_name')) {{'has-errors'}} @endif">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" class="form-control" name="middle_name" placeholder="Middle Name(s)" value="{{Input::old('middle_name')}}">
                            @if($errors->has('middle_name')) @foreach($errors->get('middle_name') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('last_name')) {{'has-errors'}} @endif">
                            <label for="last_name">Last Name (REQUIRED)</label>
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{Input::old('last_name')}}">
                            @if($errors->has('last_name')) @foreach($errors->get('last_name') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('contact_role')) {{'has-errors'}} @endif">
                            <label for="contact_role">Job Role</label>
                            <input type="text" class="form-control" name="contact_role" placeholder="Role" value="{{Input::old('contact_role')}}">
                            @if($errors->has('contact_role')) @foreach($errors->get('contact_role') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('email')) {{'has-errors'}} @endif">
                            <label for="email">Email Address (REQUIRED)</label>
                            <input type="text" class="form-control" name="email" placeholder="Email Address" value="{{Input::old('email')}}">
                            @if($errors->has('email')) @foreach($errors->get('email') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('email_confirmation')) {{'has-errors'}} @endif">
                            <label for="email_confirmation">Confirm Email (REQUIRED)</label>
                            <input type="text" class="form-control" name="email_confirmation" placeholder="Confirm Email Address">
                            @if($errors->has('email_confirmation')) @foreach($errors->get('email_confirmation') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('contact_tel')) {{'has-errors'}} @endif">
                            <label for="contact_tel">Telephone Number</label>
                            <input type="text" class="form-control" name="contact_tel" placeholder="Telephone Number" value="{{Input::old('contact_tel')}}">
                            @if($errors->has('contact_tel')) @foreach($errors->get('contact_tel') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('fax')) {{'has-errors'}} @endif">
                            <label for="fax">Fax Number</label>
                            <input type="text" class="form-control" name="fax" placeholder="Fax Number" value="{{Input::old('fax')}}">
                            @if($errors->has('fax')) @foreach($errors->get('fax') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('user_role')) {{'has-errors'}} @endif">
                            <label for="user_role">User Role (REQUIRED)</label>
                            <select name="user_role" id="user-role" class="form-control">
                                <option value="member" @if(Input::old('user_role') == 'member') selected @endif>Member</option>
                                <option value="back" @if(Input::old('user_role') == 'back') selected @endif>Admin</option>
                            </select>
                            @if($errors->has('user_role')) @foreach($errors->get('user_role') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info" style="width:100%" value="Send Activation Email">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Active Organisation Members</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" style="overflow-x:scroll;">
                            <table id="org_user_dt" class="table table-responsive table-bordered table-hover table-condensed table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Inctive Organisation Members</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" style="overflow-x:scroll;">
                            <table id="org_in_user_dt" class="table table-responsive table-bordered table-hover table-condensed table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles-bf-dyn')
    <link rel="stylesheet" href="/plugins/datatables/jquery.dataTables.css">
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('scripts-bf-dyn')
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#org_user_dt').DataTable( {
                serverSide: true,
                bSort : false,
                ajax:{
                    url:'/admin/members/active',
                    cache:true,
                    type:'POST',
                }
            } );

            $('#org_in_user_dt').DataTable( {
                serverSide: true,
                bSort : false,
                ajax:{
                    url:'/admin/members/inactive',
                    cache:true,
                    type:'POST',
                }
            } );


            $('body').on('click', '.cancel-invite', function(){
                var id = $(this).data('id');
                $.ajax({
                    type:'POST',
                    url:'/admin/members/cancel-invite',
                    data:{
                        id:id
                    },
                    success:function(response){
                        alert('Invite Cancelled');
                        var table = $('#org_in_user_dt').DataTable();
                        table.ajax.reload();
                    },
                    error:function(response){
                        console.log('response');
                    }
                });
            });

            $('body').on('click', '.resend-invite', function(){
                var id = $(this).data('id');
                $.ajax({
                    type:'POST',
                    url:'/admin/members/resend-invite',
                    data:{
                        id:id
                    },
                    success:function(response){
                        alert('Invite Resent');
                        var table = $('#org_in_user_dt').DataTable();
                        table.ajax.reload();
                    },
                    error:function(response){
                        console.log(response);
                    }
                });
            });
        } );
    </script>
@endsection

