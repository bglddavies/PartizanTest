@if(\Session::has('success'))
    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i>Success!</h4>
                @foreach(\Session::get('success') as $aSuccess)
                    {{{$aSuccess}}}<br>
                @endforeach
            </div>
        </div>
    </div>
@endif