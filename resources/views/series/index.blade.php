@extends('layout')

@section('cabecalho')
Lista de séries
@endsection

@section('conteudo')
<a href="/series/criar" class="btn btn-dark mb-2">Adicionar Série</a>

<ul class="list-group">
    @foreach($series as $serie)
        <li class="list-group-item"> {{$serie->nome}} </li>
    @endforeach
</ul>
@endsection
