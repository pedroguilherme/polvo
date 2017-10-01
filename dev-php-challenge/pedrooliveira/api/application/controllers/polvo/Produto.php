<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class Produto extends REST_Controller
{
    function __construct()
    {
        parent::__construct();;
        $this->load->library('polvo/PL_Produto');
        $this->load->library('autenticacao/LB_Autenticacao');
        $this->load->library('form_validation');
    }

    /**
     * Pesquisa todos os produtos ativos
     * @return mixed
     */
    public function pesquisa_produtos_get()
    {
        $retorno = $this->pl_produto->pesquisa_produtos();
        $this->response($retorno, $retorno["http_code"]);
    }

    /**
     * Pesquisa produto por ID apenas ativos
     * @param $id_produto
     * @return mixed
     */
    public function pesquisa_produto_por_id_get($id_produto = 0)
    {
        $this->form_validation->set_data(array("id_produto" => $id_produto));
        $this->form_validation->set_rules('id_produto', 'IDPRODUTO', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else
            $retorno = $this->pl_produto->pesquisa_produto_por_id($id_produto);

        $this->response($retorno, $retorno["http_code"]);
    }

    /**
     * Insere um produto.
     * @return mixed
     */
    public function inserir_produto_post()
    {
        $this->form_validation->set_data((array)json_decode($this->input->raw_input_stream));
        $this->form_validation->set_rules('token', 'TOKEN', 'trim|required');
        $this->form_validation->set_rules('sku_id', 'SKUID', 'trim|required|is_natural');
        $this->form_validation->set_rules('nome', 'NOME', 'trim|required|min_length[2]');
        $this->form_validation->set_rules('descricao', 'DESCRICAO', 'trim|required');
        $this->form_validation->set_rules('preco_venda', 'PRECOVENDA', 'trim|required|decimal');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else {
            $retorno = $this->lb_autenticacao->valida_token($this->form_validation->validation_data["token"]);
            if($retorno["success"]) {
                unset($this->form_validation->validation_data["token"]);
                $retorno = $this->pl_produto->inserir_produto($this->form_validation->validation_data);
            }
        }
        $this->response($retorno, $retorno["http_code"]);
    }

    /**
     * Altera um produto já cadastrado
     * @return mixed
     */
    public function alterar_produto_put()
    {
        $this->form_validation->set_data((array)json_decode($this->input->raw_input_stream));
        $this->form_validation->set_rules('token', 'TOKEN', 'trim|required');
        $this->form_validation->set_rules('id', 'IDPRODUTO', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('sku_id', 'SKUID', 'trim|required|is_natural');
        $this->form_validation->set_rules('nome', 'NOME', 'trim|required|min_length[2]');
        $this->form_validation->set_rules('descricao', 'DESCRICAO', 'trim|required');
        $this->form_validation->set_rules('preco_venda', 'PRECOVENDA', 'trim|required|decimal');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else {
            $retorno = $this->lb_autenticacao->valida_token($this->form_validation->validation_data["token"]);
            if ($retorno["success"]) {
                unset($this->form_validation->validation_data["token"]);
                $retorno = $this->pl_produto->alterar_produto($this->form_validation->validation_data);
            }
        }
        $this->response($retorno, $retorno["http_code"]);
    }

    /**
     * Deleta um produto cadastrado
     * @param $token
     * @param $id_produto
     * @return bool
     */
    public function deletar_produto_delete($token = "", $id_produto = 0)
    {
        $this->form_validation->set_data(array("id_produto" => $id_produto, "token" => $token));
        $this->form_validation->set_rules('token', 'TOKEN', 'trim|required');
        $this->form_validation->set_rules('id_produto', 'IDPRODUTO', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else {
            $retorno = $this->lb_autenticacao->valida_token($token);
            if ($retorno["success"])
                $retorno = $this->pl_produto->deletar_produto($this->form_validation->validation_data["id_produto"]);
        }
        $this->response($retorno, $retorno["http_code"]);
    }
}
