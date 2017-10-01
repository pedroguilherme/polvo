<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class PL_Pedido {

    protected $CI;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('polvo/PL_Carrinho');
        $this->CI->load->model('polvo/Pedido_model', 'pedido_model', TRUE);
    }

    /**
     * Cadastra um pedido conforme o carrinho
     * @return array
     */
    public function cadastra_pedido($id_carrinho)
    {
        $valor_carrinho = $this->CI->pl_carrinho->busca_valor_total_carrinho($id_carrinho);
        if($valor_carrinho["success"] === FALSE)
            return $valor_carrinho;
        if(empty($valor_carrinho["return"]["valor_total_carrinho"]) || $valor_carrinho["return"]["valor_total_carrinho"] == "")
            return array('success' => false, "errors" => array("unknown" => "Valor do carrinho deve superior a 0"), "http_code" => 400);

        $pedido = new stdClass();
        $pedido->id_carrinho = $id_carrinho;
        $pedido->valor_total = $valor_carrinho["return"]["valor_total_carrinho"];
        $retorno = $this->CI->pedido_model->cadastro_pedido($pedido);
        if($retorno !== FALSE)
            return array('success' => true, "return" => array("id_pedido" => $retorno), "http_code" => 201);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro ao cadastrar pedido"), "http_code" => 400);
    }

    /**
     * Busca todos os pedidos
     * @return array
     */
    public function pesquisa_pedido()
    {
        $retorno = $this->CI->pedido_model->pesquisa_pedido();
        if($retorno !== FALSE) {
            foreach($retorno as &$pedido)
                $pedido["carrinho"] = $this->CI->pl_carrinho->pesquisa_carrinho($pedido["id_carrinho"])["return"];
            return array('success' => true, "return" => $retorno, "http_code" => 200);
        } else
            return array('success' => false, "errors" => array("unknown" => "Erro ao cadastrar pedido"), "http_code" => 400);
    }
}