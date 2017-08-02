<!doctype html>
<html lang="en">
<head>
@include('back.layout.head')
</head>
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">
    @include('back.layout.header')
    @include('back.layout.left-sidebar')
    @include('back.layout.content')
    @include('back.layout.right-sidebar')
</div>

@include('back.styles.styles-bf-uni')
@yield('styles-bf-dyn')
@include('back.scripts.scripts-bf-uni')
@yield('scripts-bf-dyn')
</body>
</html>