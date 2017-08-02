@extends('back.layout._layout')

@section('title')
    {{{$org->name}}}
@endsection

@section('subtitle')
    Users
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 pull-right">
            <div class="box @if(count($errors)==0) collapsed-box @endif">
                <div class="box-header">
                    <div class="h3 box-title">
                        Invite New Client User
                    </div>
                    <div class="box-tools pull-right">
                        <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa @if(count($errors)==0) fa-plus @else fa-minus @endif"></i></button>
                    </div>
                </div>
                <div class="box-body" style="display:@if(count($errors)==0) none @else block @endif;">
                    <form action="/admin/client-organisations/users/add/{{{$org->id}}}" method="post">
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
                        <div class="form-group">
                            <input type="submit" class="btn btn-info" style="width:100%" value="Send Activation Email">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="box" style="overflow-x:scroll;">
                <div class="box-header">
                    <div class="h3 box-title">
                        Active Client Users
                    </div>
                </div>
                <div class="box-body">
                    <table id="active-user-dt" class="table table-responsive table-bordered table-hover table-condensed table-striped" width="100%">
                        <thead>
                        <tr>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="box" style="overflow-x:scroll;">
                <div class="box-header">
                    <div class="h3 box-title">
                        Inactive Client Users
                    </div>
                </div>
                <div class="box-body">
                    <table id="inactive-user-dt" class="table table-responsive table-bordered table-hover table-condensed table-striped" width="100%">
                        <thead>
                        <tr>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Role</th>
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
@endsection

@section('styles-bf-dyn')
    <link rel="stylesheet" href="/plugins/datatables/jquery.dataTables.css">
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('scripts-bf-dyn')
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>

    <script>
        $(function(){
            $('#active-user-dt').DataTable( {
                serverSide: true,
                bSort : false,
                ajax:{
                    url:'/admin/client-organisations/users/get-active/{{{$org->id}}}',
                    cache:true,
                    type:'POST',
                }
            });

            $('#inactive-user-dt').DataTable( {
                serverSide: true,
                bSort : false,
                ajax:{
                    url:'/admin/client-organisations/users/get-inactive/{{{$org->id}}}',
                    cache:true,
                    type:'POST',
                }
            });

            $('body').on('click', '.cancel-invite', function(){
                var id = $(this).data('id');
                $.ajax({
                    type:'POST',
                    url:'/admin/client-organisations/users/cancel-invite',
                    data:{
                        id:id
                    },
                    success:function(response){
                        alert('Invite Cancelled');
                        var table = $('#inactive-user-dt').DataTable();
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
                    url:'/admin/client-organisations/users/resend-invite',
                    data:{
                        id:id
                    },
                    success:function(response){
                        alert('Invite Resent');
                        var table = $('#inactive-user-dt').DataTable();
                        table.ajax.reload();
                    },
                    error:function(response){
                        console.log(response);
                    }
                });
            });
        });
    </script>
@endsection