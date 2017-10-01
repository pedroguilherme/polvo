/**
 * Created by PedroGuilherme on 06/09/2017.
 */
angular.module('app.services', [])
    .service('AuthService', ['hostApi', '$http', function (hostApi, $http) {
        this.login = function (user) {
            console.log(user);
            return $http.post(
                hostApi + 'autenticacao/AuthUser/gera_token',
                JSON.stringify(user)
            ).then(function (response) {
                    console.log(response);
                    if (response.data.success) {
                        window.localStorage.setItem('token', response.data.return.token);
                        return true;
                    } else {
                        return response.data;
                    }
                }, function (error) {
                    console.log(error);
                    if(error.data.message != "") {
                        return error.data;
                    } else {
                        return null; // O QUE HOUVE AQUI
                    }
                });
        };
        this.valida_login = function (token) {
            return $http.post(
                hostApi + 'autenticacao/AuthUser/valida_token',
                {token:token}
            ).then(function (response) {
                    if (response.data.success) {
                        return true;
                    } else {
                        return false;
                    }
                }, function (error) {
                    return false;
                });
        };
        this.logout = function () {
            window.localStorage.setItem('token', '');
            return true;
        };
    }])
    .service('Produto', ['hostApi', '$http', function (hostApi, $http) {
        this.pesquisa_produtos = function () {
            return $http.get(
                hostApi + 'polvo/Produto/pesquisa_produtos'
            ).then(function (response) {
                    return response.data
                }, function (error) {
                    return error.data;
                });
        };
        this.deletar_produto = function (produto) {
            return $http.delete(
                hostApi + 'polvo/Produto/deletar_produto/'+window.localStorage.getItem('token')+"/"+produto.id
            ).then(function (response) {
                    return response.data
                }, function (error) {
                    return error.data;
                });
        };

        this.alterar_produto = function (produto) {
            produto.token = window.localStorage.getItem('token');
            produto.preco_venda = produto.preco_venda.replace('.', '');
            produto.preco_venda = produto.preco_venda.replace(',', '.');
            produto.preco_venda = parseFloat(produto.preco_venda).toFixed(2);
            return $http.put(
                hostApi + 'polvo/Produto/alterar_produto',
                JSON.stringify(produto)
            ).then(function (response) {
                    return response.data
                }, function (error) {
                    return error.data;
                });
        };

        this.inserir_produto = function (produto) {
            produto.token = window.localStorage.getItem('token');
            produto.preco_venda = produto.preco_venda.replace('.', '');
            produto.preco_venda = produto.preco_venda.replace(',', '.');
            produto.preco_venda = parseFloat(produto.preco_venda).toFixed(2);
            return $http.post(
                hostApi + 'polvo/Produto/inserir_produto',
                JSON.stringify(produto)
            ).then(function (response) {
                    return response.data
                }, function (error) {
                    return error.data;
                });
        };
    }])
    .service('Pedido', ['hostApi', '$http', function (hostApi, $http) {
        this.pesquisa_pedido = function () {
            return $http.get(
                hostApi + 'polvo/Pedido/pesquisa_pedido/'+window.localStorage.getItem('token')
            ).then(function (response) {
                    return response.data
                }, function (error) {
                    return error.data;
                });
        }
        this.cadastra_pedido = function (id_carrinho) {
            return $http.post(
                hostApi + 'polvo/Pedido/cadastra_pedido/',
                {id_carrinho: id_carrinho}
            ).then(function (response) {
                    return response.data
                }, function (error) {
                    return error.data;
                });
        }
    }])
    .service('Carrinho', ['hostApi', '$http', function (hostApi, $http) {
        this.pesquisa_carrinho = function (id_carrinho) {
            return $http.get(
                hostApi + 'polvo/Carrinho/pesquisa_carrinho/'+id_carrinho
            ).then(function (response) {
                    return response.data
                }, function (error) {
                    return error.data;
                });
        };
        this.inserir_produto_carrinho = function (produtoCarrinho) {
            return $http.post(
                hostApi + 'polvo/Carrinho/inserir_produto_carrinho/',
                JSON.stringify(produtoCarrinho)
            ).then(function (response) {
                    return response.data
                }, function (error) {
                    return error.data;
                });
        };
        this.alterar_produto_carrinho = function (produtoCarrinho) {
            return $http.put(
                hostApi + 'polvo/Carrinho/alterar_produto_carrinho/',
                JSON.stringify(produtoCarrinho)
            ).then(function (response) {
                    return response.data
                }, function (error) {
                    return error.data;
                });
        };
        this.deletar_produto_carrinho = function (produtoCarrinho) {
            return $http.delete(
                hostApi + 'polvo/Carrinho/deletar_produto_carrinho/'+produtoCarrinho.id_carrinho+'/'+produtoCarrinho.id_produto
            ).then(function (response) {
                    return response.data
                }, function (error) {
                    return error.data;
                });
        };
    }]);