<!doctype html>
<html lang="en">
<head>
@include('login.layout.head')
@include('login.styles.styles-af-uni')
@yield('styles-af-dyn')
@include('login.scripts.scripts-af-uni')
@yield('scripts-af-dyn')
</head>
<body>
@yield('body')
@include('login.styles.styles-bf-uni')
@yield('styles-bf-dyn')
@include('login.scripts.scripts-bf-uni')
@yield('login.scripts-bf-dyn')
</body>
</html>