<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class Pedido extends REST_Controller
{
    function __construct()
    {
        parent::__construct();;
        $this->load->library('polvo/PL_Pedido');
        $this->load->library('autenticacao/LB_Autenticacao');
        $this->load->library('form_validation');
    }

    /**
     * Cadastra um pedido conforme o carrinho
     * @return array
     */
    public function cadastra_pedido_post()
    {
        $this->form_validation->set_data((array)json_decode($this->input->raw_input_stream));
        $this->form_validation->set_rules('id_carrinho', 'IDCARRINHO', 'trim|required|is_natural_no_zero');
        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else
            $retorno = $this->pl_pedido->cadastra_pedido($this->form_validation->validation_data["id_carrinho"]);
        $this->response($retorno, $retorno["http_code"]);
    }

    /**
     * Busca todos os pedidos
     * @param $token
     * @return array
     */
    public function pesquisa_pedido_get($token = '')
    {
        $this->form_validation->set_data(array("token" => $token));
        $this->form_validation->set_rules('token', 'TOKEN', 'trim|required');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else {
            $retorno = $this->lb_autenticacao->valida_token($token);
            if ($retorno["success"])
                $retorno = $this->pl_pedido->pesquisa_pedido();
        }

        $this->response($retorno, $retorno["http_code"]);
    }
}
