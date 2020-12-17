@extends('layout.app', ["current" => "Produtos"])

@section('body')

<div class="card border">
    <div class="card-body">
        <form action="{route('produtos.store')}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nome Produto">Nome da Categoria</label>
                <input type="text" class="form-control" name="nomeCategoria" 
                       id="nomeCategoria" placeholder="Categoria">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
            <button type="cancel" class="btn btn-danger btn-sm">Cancel</button>
        </form>
    </div>
</div>

@endsection