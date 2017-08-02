<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <div class="control-sidebar-container">
        <h3>This weeks logins</h3>
        <ul>
            @foreach($loginItems as $item)
                <li>{{$item->dt}} : {{{$item->ip}}} - {{{$item->user_agent}}}</li>
            @endforeach
        </ul>
    </div>
</aside>
<div class="control-sidebar-bg"></div>