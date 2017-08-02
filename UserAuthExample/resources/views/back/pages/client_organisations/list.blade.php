@extends('back.layout._layout')

@section('title')
    Client Organisations
@endsection

@section('subtitle')
    View Organisations
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 pull-right">
            <div class="box @if(count($errors)==0) collapsed-box @endif">
                <div class="box-header">
                    <h3 class="box-title">Add New Client Organisation</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa @if(count($errors)==0) fa-plus @else fa-minus @endif"></i></button>
                    </div>
                </div>
                <div class="box-body" style="display:@if(count($errors)==0) none @else block @endif;">
                    <form action="/admin/client-organisations/add" method="post">
                        <input type="hidden" name="_token" value="{{{csrf_token()}}}">
                        <div class="form-group @if($errors->has('name')) {{'has-errors'}} @endif" id="name">
                            <label for="name">Name (REQUIRED)</label>
                            <input class="form-control" type="text" name="name" placeholder="Organisation Name" value="{{{Input::old('name')}}}">
                            @if($errors->has('name')) @foreach($errors->get('name') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('al1')) {{'has-errors'}} @endif" id="al1">
                            <label for="al1">Address Line 1 (REQUIRED)</label>
                            <input class="form-control" type="text" name="al1" placeholder="Address Line 1" value="{{{Input::old('al1')}}}">
                            @if($errors->has('al1')) @foreach($errors->get('al1') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('al2')) {{'has-errors'}} @endif" id="al2">
                            <label for="al2">Address Line 2</label>
                            <input class="form-control" type="text" name="al2" placeholder="Address Line 2" value="{{{Input::old('al2')}}}">
                            @if($errors->has('al2')) @foreach($errors->get('al2') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('town')) {{'has-errors'}} @endif" id="town">
                            <label for="town">Town (REQUIRED)</label>
                            <input class="form-control" type="text" name="town" placeholder="Town" value="{{{Input::old('town')}}}">
                            @if($errors->has('town')) @foreach($errors->get('town') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('region')) {{'has-errors'}} @endif" id="region">
                            <label for="region">Region (REQUIRED)</label>
                            <input class="form-control" type="text" name="region" placeholder="Region" value="{{{Input::old('region')}}}">
                            @if($errors->has('region')) @foreach($errors->get('region') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('postcode')) {{'has-errors'}} @endif" id="postcode">
                            <label for="postcode">Postcode (REQUIRED)</label>
                            <input class="form-control" type="text" name="postcode" placeholder="Postcode" value="{{{Input::old('postcode')}}}">
                            @if($errors->has('postcode')) @foreach($errors->get('postcode') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('country')) {{'has-errors'}} @endif" id="country">
                            <label for="country">Country (REQUIRED)</label>
                            <input class="form-control" type="text" name="country" placeholder="Country" value="{{{Input::old('country')}}}">
                            @if($errors->has('country')) @foreach($errors->get('country') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('vat_number')) {{'has-errors'}} @endif" id="vat_number">
                            <label for="name">VAT Number</label>
                            <input class="form-control" type="text" name="vat_number" placeholder="VAT Number" value="{{{Input::old('vat_number')}}}">
                            @if($errors->has('vat_number')) @foreach($errors->get('vat_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('company_number')) {{'has-errors'}} @endif" id="company_number">
                            <label for="company_number">Company Number</label>
                            <input class="form-control" type="text" name="company_number" placeholder="Company Number" value="{{{Input::old('company_number')}}}">
                            @if($errors->has('company_number')) @foreach($errors->get('company_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('phone_number')) {{'has-errors'}} @endif" id="phone_number">
                            <label for="phone_number">Phone Number</label>
                            <input class="form-control" type="text" name="phone_number" placeholder="Phone Number" value="{{{Input::old('phone_number')}}}">
                            @if($errors->has('phone_number')) @foreach($errors->get('phone_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('fax_number')) {{'has-errors'}} @endif" id="fax_number">
                            <label for="fax_number">Fax Number</label>
                            <input class="form-control" type="text" name="fax_number" placeholder="Fax Number" value="{{{Input::old('fax_number')}}}">
                            @if($errors->has('fax_number')) @foreach($errors->get('fax_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('organisation_type')) {{'has-errors'}} @endif">
                            <label for="company_type_id">Organisation Type (REQUIRED)</label>
                            <select name="company_type_id" id="company_type_id" class="form-control">
                                @foreach($types as $type)
                                    <option value="{{$type->id}}" @if(Input::old('company_type_id') == $type->id) selected @endif>{{{$type->type}}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('company_type_id')) @foreach($errors->get('company_type_id') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info" style="width:100%" value="Update Details">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="box" style="overflow-x:scroll;">
                <div class="box-header">
                    <h3 class="box-title">Client Organisations</h3>
                </div>
                <div class="box-body">
                    <table id="client_org_dt" class="table table-responsive table-bordered table-hover table-condensed table-striped" width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>VAT Number</th>
                            <th>Company Number</th>
                            <th>Phone Number</th>
                            <th>Active Users</th>
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
            $('#client_org_dt').DataTable({
                serverSide: true,
                bSort : false,
                ajax:{
                    url:'/admin/client-organisations/get-clients-dt',
                    cache:true,
                    type:'POST',
                }
            });
        })
    </script>
@endsection