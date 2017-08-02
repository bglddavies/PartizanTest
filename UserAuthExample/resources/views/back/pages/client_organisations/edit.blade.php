@extends('back.layout._layout')

@section('title')
    {{{$org->name}}}
@endsection

@section('subtitle')
    Edit Organisation
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Quick Links
                    </h3>
                </div>
                <div class="box-body quick-links">
                    <a href="/admin/client-organisations/view-users/{{{$org->id}}}" class="btn btn-info">Jump to {{{$org->name}}} users</a>
                    <a href="/admin/notification-center/organisation/{{{$org->id}}}" class="btn btn-info">Jump to organisation notifications</a>
                    <a href="/admin/transfers/users/{{{$org->id}}}" class="btn btn-info">Jump to organisation transfers</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">
                                Client Organisation Relevant Dates
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
                <div class="col-xs-12">
                    <div class="box collapsed-box">
                        <div class="box-header">
                            <h3 class="box-title">Delete Organisation and Users</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="box-body" style="display:none;">
                            <div class="btn btn-danger" id="delete-organisation">Delete</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Organisation Details</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" style="">
                    <form action="/admin/client-organisations/view/edit/{{{$org->id}}}" method="post">
                        <input type="hidden" name="_token" value="{{{csrf_token()}}}">
                        <div class="form-group @if($errors->has('name')) {{'has-errors'}} @endif" id="name">
                            <label for="name">Name (REQUIRED)</label>
                            <input class="form-control" type="text" name="name" placeholder="Organisation Name" value="{{{$org->name}}}">
                            @if($errors->has('name')) @foreach($errors->get('name') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('al1')) {{'has-errors'}} @endif" id="al1">
                            <label for="al1">Address Line 1 (REQUIRED)</label>
                            <input class="form-control" type="text" name="al1" placeholder="Address Line 1" value="{{{$org->al1}}}">
                            @if($errors->has('al1')) @foreach($errors->get('al1') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('al2')) {{'has-errors'}} @endif" id="al2">
                            <label for="al2">Address Line 2</label>
                            <input class="form-control" type="text" name="al2" placeholder="Address Line 2" value="{{{$org->al2}}}">
                            @if($errors->has('al2')) @foreach($errors->get('al2') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('town')) {{'has-errors'}} @endif" id="town">
                            <label for="town">Town (REQUIRED)</label>
                            <input class="form-control" type="text" name="town" placeholder="Town" value="{{{$org->town}}}">
                            @if($errors->has('town')) @foreach($errors->get('town') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('region')) {{'has-errors'}} @endif" id="region">
                            <label for="region">Region (REQUIRED)</label>
                            <input class="form-control" type="text" name="region" placeholder="Region" value="{{{$org->region}}}">
                            @if($errors->has('region')) @foreach($errors->get('region') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('postcode')) {{'has-errors'}} @endif" id="postcode">
                            <label for="postcode">Postcode (REQUIRED)</label>
                            <input class="form-control" type="text" name="postcode" placeholder="Postcode" value="{{{$org->postcode}}}">
                            @if($errors->has('postcode')) @foreach($errors->get('postcode') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('country')) {{'has-errors'}} @endif" id="country">
                            <label for="country">Country (REQUIRED)</label>
                            <input class="form-control" type="text" name="country" placeholder="Country" value="{{{$org->country}}}">
                            @if($errors->has('country')) @foreach($errors->get('country') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('vat_number')) {{'has-errors'}} @endif" id="vat_number">
                            <label for="name">VAT Number</label>
                            <input class="form-control" type="text" name="vat_number" placeholder="VAT Number" value="{{{$org->vat_number}}}">
                            @if($errors->has('vat_number')) @foreach($errors->get('vat_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('company_number')) {{'has-errors'}} @endif" id="company_number">
                            <label for="company_number">Company Number</label>
                            <input class="form-control" type="text" name="company_number" placeholder="Company Number" value="{{{$org->company_number}}}">
                            @if($errors->has('company_number')) @foreach($errors->get('company_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('phone_number')) {{'has-errors'}} @endif" id="phone_number">
                            <label for="phone_number">Phone Number</label>
                            <input class="form-control" type="text" name="phone_number" placeholder="Phone Number" value="{{{$org->phone_number}}}">
                            @if($errors->has('phone_number')) @foreach($errors->get('phone_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('fax_number')) {{'has-errors'}} @endif" id="fax_number">
                            <label for="fax_number">Fax Number</label>
                            <input class="form-control" type="text" name="fax_number" placeholder="Fax Number" value="{{{$org->fax_number}}}">
                            @if($errors->has('fax_number')) @foreach($errors->get('fax_number') as $error) <div class="error-content">{{$error}}</div>@endforeach @endif
                        </div>
                        <div class="form-group @if($errors->has('organisation_type')) {{'has-errors'}} @endif">
                            <label for="company_type_id">Organisation Type (REQUIRED)</label>
                            <select name="company_type_id" id="company_type_id" class="form-control">
                                @foreach($types as $type)
                                    <option value="{{$type->id}}" @if($org->company_type_id == $type->id) selected @endif>{{{$type->type}}}</option>
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
        $(function(){
            getOrganisationRelatedDates();

            function getOrganisationRelatedDates(){
                $.ajax({
                    url:'/admin/client-organisations/view/get-related-dates/{{{$org->id}}}',
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
                    url:'/admin/client-organisations/view/save-related-date/{{{$org->id}}}',
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

            $('#delete-organisation').click(function(){
                if(window.confirm("Really delete this organisation and its users? This action cannot be undone"))
                {
                    $.ajax({
                        type:'POST',
                        url:'/admin/client-organisations/view/delete/{{{$org->id}}}',
                        success:function(response){
                            window.location.replace('/admin/client-organisations/');
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

