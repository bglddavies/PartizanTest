@if(\Session::has('errors_custom'))
    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i>Aborted!</h4>
                @foreach(\Session::get('errors_custom') as $anError)
                    {{{$anError}}}<br>
                @endforeach
            </div>
        </div>
    </div>
@endif