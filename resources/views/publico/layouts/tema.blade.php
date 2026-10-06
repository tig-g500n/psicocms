@php($tema = $tema ?? config('psicocms.tema_por_defecto'))
@php($temaUri = config('psicocms.themes_uri').'/'.$tema)
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', config('psicocms.nombre'))</title>
    <meta name="description" content="@yield('descripcion', '')">

    <link href="{{ asset('assets/fonts/fontawesome-free-6.1.2-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset($temaUri.'/css/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset($temaUri.'/css/reset.css') }}" rel="stylesheet">
    <link href="{{ asset($temaUri.'/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset($temaUri.'/css/responsive.css') }}" rel="stylesheet">
    @stack('estilos')
</head>

<body>
    @yield('contenido')
    @stack('scripts')
</body>

</html>
