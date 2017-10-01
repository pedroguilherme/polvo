<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class PL_Produto
{
    protected $CI;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('polvo/Produto_model', 'produto_model', TRUE);
    }

    /**
     * Pesquisa todos os produtos ativos
     * @return mixed
     */
    public function pesquisa_produtos()
    {
        $retorno = $this->CI->produto_model->pesquisa_produtos();
        if ($retorno !== FALSE)
            return array('success' => true, "return" => $retorno, "http_code" => 200);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro ao buscar produtos ou produtos não encontrados"), "http_code" => 400);
    }

    /**
     * Pesquisa produto por ID apenas ativos
     * @param $id_produto
     * @return mixed
     */
    public function pesquisa_produto_por_id($id_produto)
    {
        $retorno = $this->CI->produto_model->pesquisa_produto_por_id($id_produto);
        if ($retorno !== FALSE)
            return array('success' => true, "return" => $retorno, "http_code" => 200);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro ao buscar produto ou produto não encontrado"), "http_code" => 400);
    }

    /**
     * Insere um produto.
     * @param $data
     * @return mixed
     */
    public function inserir_produto($data)
    {
        $retorno = $this->CI->produto_model->inserir_produto($data);
        if ($retorno !== FALSE)
            return array('success' => true, "return" => $retorno, "http_code" => 200);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro ao inserir produto"), "http_code" => 400);
    }

    /**
     * Altera um produto já cadastrado
     * @param $data
     * @return mixed
     */
    public function alterar_produto($data)
    {
        $retorno = $this->CI->produto_model->alterar_produto(
            array(
                "sku_id" => $data["sku_id"],
                "nome" => $data["nome"],
                "descricao" => $data["descricao"],
                "preco_venda" => $data["preco_venda"]
            ),
            array(
                "id" => $data["id"]
            )
        );
        if ($retorno !== FALSE)
            return array('success' => true, "return" => $retorno, "http_code" => 200);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro alterar produto"), "http_code" => 400);
    }

    /**
     * Deleta um produto cadastrado
     * @param $id_produto
     * @return bool
     */
    public function deletar_produto($id_produto)
    {
        $retorno = $this->CI->produto_model->deletar_produto(array("id" => $id_produto));
        if ($retorno !== FALSE)
            return array('success' => true, "return" => $retorno, "http_code" => 200);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro deletar produto"), "http_code" => 400);
    }
}