<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/c2b096e728.js" crossorigin="anonymous"></script>
    <title>Controle de Séries</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-2 d-flex justify-content-between">
        <a class="navbar navbar-brand" href="{{ route('listar-series') }}">Home</a>

        @auth
        <a href="/sair" class="text-danger">Sair</a>
        @endauth

        @guest
        <a href="{{route('listar-series')}}">Entrar como convidado</a>
        @endguest
    </nav>

    <div class="container">
        <div class="jumbotron">
            <h1>@yield('cabecalho')</h1>
        </div>        
        @yield('conteudo')
    </div>

</body>
</html>