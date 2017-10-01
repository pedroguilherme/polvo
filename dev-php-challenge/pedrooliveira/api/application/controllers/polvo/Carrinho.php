<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class Carrinho extends REST_Controller
{
    function __construct()
    {
        parent::__construct();;
        $this->load->library('polvo/PL_Carrinho');
        $this->load->library('form_validation');
    }

    /**
     * Pesquisa um carrinho pelo id
     * @param $id_carrinho
     * @return mixed
     */
    public function pesquisa_carrinho_get($id_carrinho = 0)
    {
        $this->form_validation->set_data(array("id_carrinho" => $id_carrinho));
        $this->form_validation->set_rules('id_carrinho', 'IDCARRINHO', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else
            $retorno = $this->pl_carrinho->pesquisa_carrinho($this->form_validation->validation_data["id_carrinho"]);

        $this->response($retorno, $retorno["http_code"]);
    }

    /**
     * Busca o valor total do carrinho
     * @param $id_carrinho
     * @return mixed
     */
    public function busca_valor_total_carrinho_get($id_carrinho = 0)
    {
        $this->form_validation->set_data(array("id_carrinho" => $id_carrinho));
        $this->form_validation->set_rules('id_carrinho', 'IDCARRINHO', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else
            $retorno = $this->pl_carrinho->busca_valor_total_carrinho($this->form_validation->validation_data["id_carrinho"]);

        $this->response($retorno, $retorno["http_code"]);
    }

    /**
     * Insere um produto em um carrinho.
     *
     * @return mixed
     */
    public function inserir_produto_carrinho_post()
    {
        $this->form_validation->set_data((array)json_decode($this->input->raw_input_stream));
        $this->form_validation->set_rules('id_carrinho', 'IDCARRINHO', 'trim|required');
        $this->form_validation->set_rules('id_produto', 'IDPRODUTO', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('quantidade', 'QUANTIDADE', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else
            $retorno = $this->pl_carrinho->inserir_produto_carrinho($this->form_validation->validation_data);

        $this->response($retorno, $retorno["http_code"]);
    }

    /**
     * Altera um produto já cadastrado no carrinho
     *
     * @return mixed
     */
    public function alterar_produto_carrinho_put()
    {
        $this->form_validation->set_data((array)json_decode($this->input->raw_input_stream));
        $this->form_validation->set_rules('id_carrinho', 'IDCARRINHO', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('id_produto', 'IDPRODUTO', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('quantidade', 'QUANTIDADE', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else
            $retorno = $this->pl_carrinho->alterar_produto_carrinho(
                array(
                    "quantidade" => $this->form_validation->validation_data["quantidade"]
                ),
                array(
                    "id_produto" => $this->form_validation->validation_data["id_produto"],
                    "id_carrinho" => $this->form_validation->validation_data["id_carrinho"]
                )
            );

        $this->response($retorno, $retorno["http_code"]);
    }

    /**
     * Deleta um produto já cadastrado no carrinho
     * @param $id_carrinho
     * @param $id_produto
     * @return mixed
     */
    public function deletar_produto_carrinho_delete($id_carrinho = 0, $id_produto = 0)
    {
        $this->form_validation->set_data(array("id_carrinho" => $id_carrinho, "id_produto" => $id_produto));
        $this->form_validation->set_rules('id_carrinho', 'IDCARRINHO', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('id_produto', 'IDPRODUTO', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else
            $retorno = $this->pl_carrinho->deletar_produto_carrinho($this->form_validation->validation_data);

        $this->response($retorno, $retorno["http_code"]);
    }
}
