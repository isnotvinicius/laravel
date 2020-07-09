@extends('layout')

@section('cabecalho')
Inserir Série
@endsection

@section('conteudo')
<form method="post">
    @csrf
    <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome">
    </div>
    <button class="btn btn-primary">Adicionar Série</button>
</form>
@endsection
