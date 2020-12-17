@extends('layout.app', ["current" => "produtos" ])

@section('body')
<div class="card border">
    <div class="card-body">
        <h5 class="card-title">Cadastro de Produtos</h5>
        <table class="table table-ordered table-hover" id="tabelaProdutos">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Departamento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                              
            </tbody>
        </table>       
    </div>
    <div class="card-footer">
        <button class="btn btn-sm btn-primary" role="button" onclick='novoProduto()'>Novo produto</button>
    </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="dlgProdutos">
    <div class="modal-dialog" role="document">
        <div class="modal-content p-2">
            <form id="formProduto" action="{{route('produtos.store')}}" method="POST" class="form-horizontal">
                <div class="model-header">
                    <h5 class="modal-title">Novo Produto</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" class="form-control">
                    <div class="form-group">
                        <label for="nomeProduto" class="control-label">Nome do Produto</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" placeholder="Nome do Produto">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="quantidade" class="control-label">Quantidade</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="Quantidade">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="preco" class="control-label">Preço do Produto</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="preco" name="preco" placeholder="Preço do produto">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="departamento" class="control-label">Departamento</label>
                        <div class="input-group">
                            <select type="text" class="form-control" id="departamento" name="departamento">
                                <option value="">Selecione</option>
                            </select>
                        </div>
                    </div>

               
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div> 
            </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    function novoProduto() {
        $('#id').val('');
        $('#nomeProduto').val('');
        $('#preco').val('');
        $('#quantidade').val('');
        $('#dlgProdutos').modal('show');
    }

    function carregarCategorias(){
        $.getJSON('/api/categorias', function(data){ 
            console.log(data);
            for(i=0; i<data.length; i++){
                opcao = '<option value = "' + data[i].id + '">'+data[i].nome+'</option>';
                $('#departamento').append(opcao);
            }
        });
    }

    $('#formProduto').submit(function(event){
        event.preventDefault();
        if($('#id').val() != ""){
            salvarProduto();
        }else{
            CriarProduto();
        }
        
        $('#dlgProdutos').modal('hide');
    });

    $(function(){
        carregarCategorias();
        carregarProdutos();
    })

    function CriarProduto(){
        prod = {
            nome: $('#nomeProduto').val(),
            estoque: $('#quantidade').val(),
            preco: $('#preco').val(),
            categoria_id: $('#departamento').val()
        };
        $.post("/api/produtos", prod, function(data){
            console.log(data);
        });
    }

    function editar(id){
        $.getJSON('/api/produtos/'+id, function(data){
            $('#id').val(data.id);
            $('#nomeProduto').val(data.nome);
            $('#preco').val(data.preco);
            $('#quantidade').val(data.estoque);
            $('#departamento').val(data.categoria_id);
            $('#dlgProdutos').modal('show');
        });
    }

    function salvarProduto(){
        prod = {
            id: $('#id').val(),
            nome: $('#nomeProduto').val(),
            estoque: $('#quantidade').val(),
            preco: $('#preco').val(),
            categoria_id: $('#departamento').val()
        };
        $.ajax({
            type: "PUT",
            url: "/api/produtos/"+ prod.id,
            context: this,
            data: prod,
            success: function(){
                console.log('Ok');
            },
            error: function(error){
                console.log();
            }
        });
    }

    function deletar(id){
        $.ajax({
            type: "DELETE",
            url: "/api/produtos/"+id,
            context: this,
            success: function(){
                linhas = $('#tabelaProdutos>tbody>tr');
                index = linhas.filter(function(i, elemento){
                    return elemento.cells[0].textContent == id;
                });
                if(index){
                    index.remove();
                }
            },
            error: function(error){
               
            }
        });
    }

    function montarLinha(produtos){
        var linha = '<tr>'+
                        '<td>'+produtos.id+'</td>'+
                        '<td>'+produtos.nome+'</td>'+
                        '<td>'+produtos.estoque+'</td>'+
                        '<td>'+produtos.preco+'</td>'+
                        '<td>'+produtos.categoria_id+'</td>'+
                        '<td>'+
                            '<button class="btn btn-sm btn-primary mr-1" onclick="editar('+produtos.id+')"> Editar </button>'+
                            '<button class="btn btn-sm btn-danger" onclick="deletar('+produtos.id+')"> Deletar </button>'+
                        '</td>'+
                    '</tr>';
                    return linha;
    }

    function carregarProdutos(){
        $.getJSON('/api/produtos', function(produtos){
            for(i=0; i<produtos.length; i++){
                linha = montarLinha(produtos[i]);
                $('#tabelaProdutos>tbody').append(linha);                    
            }
        });
    }
</script>


@endsection