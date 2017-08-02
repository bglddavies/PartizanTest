<div class="content-wrapper">
    <section class="content-header">
        <h1>
            @yield('title')
            <small>@yield('subtitle')</small>
        </h1>
    </section>
    <section class="content" style="padding-top:40px;">
        @include('back.layout.error_bar')
        @include('back.layout.success_bar')
        @yield('content')
    </section>
</div>