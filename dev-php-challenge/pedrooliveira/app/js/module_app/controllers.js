/**
 * Created by PedroGuilherme on 06/09/2017.
 */
angular.module('app.controllers', [])
    .controller("polvoLoginControl", function ($scope, AuthService) {
        $scope.login = function(user){
            AuthService.login(user).then(function(response){
                if(response != true && response != null) {
                    var msg = "";
                    $.each(response.errors, function (key, val) {
                        msg += val;
                    });
                    swal('Ops, algo deu errado!', msg, 'error');
                    return false;
                } else if(response){
                    location.href='painel/';
                    return false;
                }
            });
        };
        $scope.valida_login = function(){
            if(window.localStorage.getItem('token') != undefined) {
                AuthService.valida_login(window.localStorage.getItem('token')).then(function (response) {
                    console.log(response);
                    if (response != true) {
                        location.href = "../";
                    }
                });
            } else {
                location.href = "../";
            }
        };
        $scope.sair = function(){
            AuthService.logout();
            location.href = "../";
        };
    })
    .controller("polvoPedidosControl", function ($scope, Pedido) {
        $scope.pesquisa_pedido = function(){
            Pedido.pesquisa_pedido().then(function(response){
                if(response.success) {
                    $scope.pedidos = response.return;
                } else {
                    var msg = "";
                    $.each(response.errors, function (key, val) {
                        msg += val;
                    });
                    swal('Ops, algo deu errado!', msg, 'error');
                }
            });
        };

        $scope.cadastra_pedido = function(){
            Pedido.cadastra_pedido(window.localStorage.getItem('id_carrinho')).then(function(response){
                if(response.success){
                    swal({
                        title: "Pedido fechado, número: "+response.return.id_pedido,
                        text: "Dev-Challenge Complete!",
                        type: "success"
                    }, function() {
                        window.localStorage.setItem('id_carrinho', 0);
                        location.reload();
                    });
                } else {
                    var msg = "";
                    $.each(response.errors, function (key, val) {
                        msg += val;
                    });
                    swal('Ops, algo deu errado!', msg, 'error');
                }
            });
        };
    })
    .controller("polvoCarrinhoControl",function ($scope, Carrinho) {
        $scope.id_carrinho = window.localStorage.getItem('id_carrinho') == undefined ? 0: window.localStorage.getItem('id_carrinho');
        $scope.total_carrinho = 0;
        $scope.pesquisa_carrinho = function(){
            Carrinho.pesquisa_carrinho($scope.id_carrinho).then(function(response){
                if(response.success) {
                    $scope.total_carrinho = 0;
                    $.each(response.return, function(key, val){
                        response.return[key].quantidade = parseInt(val.quantidade);
                        $scope.total_carrinho += parseFloat(val.valor_total_produto);
                    });
                    $scope.carrinho = response.return;
                } else {
                }
            });
        };

        $scope.inserir_produto_carrinho = function(produtoCarrinho){
            var produto = {id_produto: produtoCarrinho.id, quantidade: 1, id_carrinho: $scope.id_carrinho};
            Carrinho.inserir_produto_carrinho(produto).then(function(response){
                if(response.success) {
                    swal('Produto adicionado no carrinho!', '', 'success');
                    if($scope.id_carrinho == 0) {
                        window.localStorage.setItem('id_carrinho', response.return);
                        $scope.id_carrinho = response.return;
                    }
                    $scope.pesquisa_carrinho();
                } else {
                    var msg = "";
                    $.each(response.errors, function (key, val) {
                        msg += val;
                    });
                    swal('Ops, algo deu errado!', msg, 'error');
                }
            });
        };

        $scope.alterar_produto_carrinho = function(produtoCarrinho){
            var produto = {id_produto: produtoCarrinho.id_produto, quantidade: produtoCarrinho.quantidade, id_carrinho: $scope.id_carrinho};
            Carrinho.alterar_produto_carrinho(produto).then(function(response){
                if(response.success) {
                    $scope.pesquisa_carrinho();
                } else {
                    var msg = "";
                    $.each(response.errors, function (key, val) {
                        msg += val;
                    });
                    swal('Ops, algo deu errado!', msg, 'error');
                }
            });
        };

        $scope.deletar_produto_carrinho = function(produtoCarrinho){
            var produto = {id_produto: produtoCarrinho.id_produto, id_carrinho: $scope.id_carrinho};
            Carrinho.deletar_produto_carrinho(produto).then(function(response){
                if(response.success) {
                    $scope.pesquisa_carrinho();
                } else {
                    var msg = "";
                    $.each(response.errors, function (key, val) {
                        msg += val;
                    });
                    swal('Ops, algo deu errado!', msg, 'error');
                }
            });
        };
    })
    .controller("polvoProdutosControl",function ($scope, $filter, Produto) {
        $scope.acao = false;
        $scope.produto = {};
        $scope.pesquisa_produtos = function(){
            Produto.pesquisa_produtos().then(function(response){
                if(response.success) {
                    $scope.produtos = response.return;
                } else {
                    var msg = "";
                    $.each(response.errors, function (key, val) {
                        msg += val;
                    });
                    swal('Ops, algo deu errado!', msg, 'error');
                }
            });
        };
        $scope.deletar_produto = function(produto){
            swal({
                title: "Tem certeza que deseja deletar esse item?",
                text: "Você não poderá recuperar esse produto depois de deletar.",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Deletar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            },function(isConfirm) {
                if (isConfirm) {
                    Produto.deletar_produto(produto).then(function(response){
                        if(response.success) {
                            swal('Produto deletado', '', 'success');
                            $scope.pesquisa_produtos();
                        } else {
                            var msg = "";
                            $.each(response.errors, function (key, val) {
                                msg += val;
                            });
                            swal('Ops, algo deu errado!', msg, 'error');
                        }
                    });
                }
            });
        };
        $scope.alterar_produto = function(produto){
            Produto.alterar_produto(produto).then(function(response){
                if(response.success) {
                    swal('Produto alterado', '', 'success');
                    $scope.pesquisa_produtos();
                } else {
                    var msg = "";
                    $.each(response.errors, function (key, val) {
                        msg += val;
                    });
                    swal('Ops, algo deu errado!', msg, 'error');
                }
            });
        };
        $scope.inserir_produto = function(produto){
            Produto.inserir_produto(produto).then(function(response){
                if(response.success) {
                    swal('Produto cadastrado', '', 'success');
                    $scope.pesquisa_produtos();
                } else {
                    var msg = "";
                    $.each(response.errors, function (key, val) {
                        msg += val;
                    });
                    swal('Ops, algo deu errado!', msg, 'error');
                }
            });
        };
        $scope.produtoAction = function(produto, acao){
            console.log(produto);
            if(acao == 'editar') {
                $scope.alterar_produto(produto);
            } else {
                $scope.inserir_produto(produto);
            }
            $scope.produto = {};
            $scope.acao = false;
        };
        $scope.trocaAcao = function(acao, produto){
            $scope.acao = acao;
            if(acao == 'editar') {
                produto.preco_venda = $filter('currency')(produto.preco_venda, '', '2');
                $scope.produto = produto;
            }
        };
    });