@extends('back.layout._layout')

@section('title')
    Configuration
@endsection

@section('subtitle')
@endsection

@section('content')
    <div class="row">
        <!-- Add relevant dates -->
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Organisation Types</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered" id="org_types">
                        <thead>
                        <tr>
                            <th>
                                Title
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <br>
                    <h3>Add New Organisation Type</h3>
                    <form action="/admin/config/add-ct" method="POST" id="addCT">
                        <input type="hidden" name="_token" value="{{{csrf_token()}}}">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Add</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="title" placeholder="Title">
                                </td>
                                <td>
                                    <input type="submit" value="Add" class="btn btn-success">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles-bf-dyn')
    <style>
        #relevant_date_times input, #relevant_date_times select, #addNRDT input, #addNRDT select, #org_types input, #org_types select, #addCT input, #document-categories input, #addDocumentCategory input{
            width:100%;
        }
    </style>
@endsection

@section('scripts-bf-dyn')
    <script>
        $(function(){
            getCompanyTypes();

            function  getCompanyTypes()
            {
                //GET THE COMPANY TYPES. ONE EDIT BOX, A SAVE BTN AND A DELETE BUTTON.
                $.ajax({
                    url:'/admin/config/getCompanyTypes',
                    type:'POST',
                    success:function(response){
                        $('#org_types tbody').html('');
                        $(response).each(function(key,value){
                            var conStr = '<tr>';

                            conStr+='<td>';
                            conStr+='<input class="title" value="'+value.title+'">';
                            conStr+='</td>';

                            conStr+='<td>';
                            conStr+='<button class="btn btn-success save-ct" data-id="'+value.id+'">Save</button>&nbsp;';
                            conStr+='<button class="btn btn-danger delete-ct" data-id="'+value.id+'">Delete</button>';
                            conStr+='</td>';

                            conStr+='</tr>';

                            $('#org_types tbody').append(conStr);
                        });
                    },
                    error:function(response){
                        console.log(response);
                    }
                });
            }

            $('body').on('click', '.save-ct', function(){
                var id = $(this).data('id');
                var title = $(this).closest('tr').find('.title').val();

                $.ajax({
                    url:'/admin/config/update-ct/'+id,
                    type:'POST',
                    data:{
                        title:title
                    },
                    success:function(response){
                        getCompanyTypes();
                    },
                    error:function(response){

                    }
                })
            });

            $('body').on('click', '.delete-ct', function(){
                var id = $(this).data('id');

                $.ajax({
                   url:'/admin/config/delete-ct/'+id,
                    type:'POST',
                    success:function(response){
                        getCompanyTypes();
                    },
                    error:function(response){
                        alert('Could not delete company type.');
                    }
                });
            });
        });
    </script>
@endsection