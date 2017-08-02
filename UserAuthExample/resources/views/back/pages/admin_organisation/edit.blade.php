@extends('back.layout._layout')

@section('title')
    My Organisation
@endsection

@section('subtitle')
    Edit Organisation
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4 col-xs-12 pull-right">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">My Organisation Details</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" style="">
                    <form action="/admin/admin-organisation/edit-details" method="post">
                        <input type="hidden" name="_token" value="{{{csrf_token()}}}">
                        <div class="form-group @if($errors->has('name')) {{'has-errors'}} @endif" id="name">
                            <label for="name">Name (REQUIRED)</label>
                            <input class="form-control" type="text" name="name" placeholder="Organisation Name" value="{{{$admOrg->name}}}">
                            @if($errors->has('name')) @foreach($errors->get('name') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('al1')) {{'has-errors'}} @endif" id="al1">
                            <label for="al1">Address Line 1 (REQUIRED)</label>
                            <input class="form-control" type="text" name="al1" placeholder="Address Line 1" value="{{{$admOrg->al1}}}">
                            @if($errors->has('al1')) @foreach($errors->get('al1') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('al2')) {{'has-errors'}} @endif" id="al2">
                            <label for="al2">Address Line 2</label>
                            <input class="form-control" type="text" name="al2" placeholder="Address Line 2" value="{{{$admOrg->al2}}}">
                            @if($errors->has('al2')) @foreach($errors->get('al2') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('town')) {{'has-errors'}} @endif" id="town">
                            <label for="town">Town (REQUIRED)</label>
                            <input class="form-control" type="text" name="town" placeholder="Town" value="{{{$admOrg->town}}}">
                            @if($errors->has('town')) @foreach($errors->get('town') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('region')) {{'has-errors'}} @endif" id="region">
                            <label for="region">Region (REQUIRED)</label>
                            <input class="form-control" type="text" name="region" placeholder="Region" value="{{{$admOrg->region}}}">
                            @if($errors->has('region')) @foreach($errors->get('region') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('postcode')) {{'has-errors'}} @endif" id="postcode">
                            <label for="postcode">Postcode (REQUIRED)</label>
                            <input class="form-control" type="text" name="postcode" placeholder="Postcode" value="{{{$admOrg->postcode}}}">
                            @if($errors->has('postcode')) @foreach($errors->get('postcode') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('country')) {{'has-errors'}} @endif" id="country">
                            <label for="country">Country (REQUIRED)</label>
                            <input class="form-control" type="text" name="country" placeholder="Country" value="{{{$admOrg->country}}}">
                            @if($errors->has('country')) @foreach($errors->get('country') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('vat_number')) {{'has-errors'}} @endif" id="vat_number">
                            <label for="name">VAT Number</label>
                            <input class="form-control" type="text" name="vat_number" placeholder="VAT Number" value="{{{$admOrg->vat_number}}}">
                            @if($errors->has('vat_number')) @foreach($errors->get('vat_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('company_number')) {{'has-errors'}} @endif" id="company_number">
                            <label for="company_number">Company Number</label>
                            <input class="form-control" type="text" name="company_number" placeholder="Company Number" value="{{{$admOrg->company_number}}}">
                            @if($errors->has('company_number')) @foreach($errors->get('company_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('phone_number')) {{'has-errors'}} @endif" id="phone_number">
                            <label for="phone_number">Phone Number</label>
                            <input class="form-control" type="text" name="phone_number" placeholder="Phone Number" value="{{{$admOrg->phone_number}}}">
                            @if($errors->has('phone_number')) @foreach($errors->get('phone_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('fax_number')) {{'has-errors'}} @endif" id="fax_number">
                            <label for="fax_number">Fax Number</label>
                            <input class="form-control" type="text" name="fax_number" placeholder="Fax Number" value="{{{$admOrg->fax_number}}}">
                            @if($errors->has('fax_number')) @foreach($errors->get('fax_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('organisation_type')) {{'has-errors'}} @endif">
                            <label for="company_type_id">Organisation Type (REQUIRED)</label>
                            <select name="company_type_id" id="company_type_id" class="form-control">
                                @foreach($types as $type)
                                    <option value="{{$type->id}}" @if($admOrg->company_type_id == $type->id) selected @endif>{{{$type->type}}}</option>
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
    </div>
@endsection

@section('styles-bf-dyn')

@endsection

@section('scripts-bf-dyn')

@endsection