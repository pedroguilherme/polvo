<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class PL_Carrinho
{
    protected $CI;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('polvo/Carrinho_model', 'carrinho_model', TRUE);
        $this->CI->load->library('polvo/PL_Produto');
    }

    /**
     * Insere um produto em um carrinho.
     *
     * @param $data
     * @return mixed
     */
    public function inserir_produto_carrinho($data)
    {
        $product = $this->CI->pl_produto->pesquisa_produto_por_id($data["id_produto"]);
        if ($product["success"] !== FALSE) {
            $this->CI->carrinho_model->trans_begin();
            if($data["id_carrinho"] > 0) {
                $produto = $this->pesquisa_produto_carrinho($data["id_carrinho"], $data["id_produto"]);
                if($produto["success"] && $produto["return"]["id_produto"] == $data["id_produto"]) {
                    $retorno = $this->alterar_produto_carrinho(
                        array("quantidade"=> ($data["quantidade"]+$produto["return"]["quantidade"])),
                        array("id"=>$produto["return"]["id"])
                    );
                    $this->CI->carrinho_model->trans_commit();
                    return $retorno;
                }
            } else
                $data["id_carrinho"] = NULL;

            $retorno = $this->CI->carrinho_model->inserir_produto_carrinho($data);
            if ($retorno !== FALSE) {
                if ($data["id_carrinho"] == 0) {
                    $rate = $this->alterar_produto_carrinho(array("id_carrinho" => $retorno), array("id" => $retorno));
                    if (!$rate["success"]) {
                        $this->CI->carrinho_model->trans_rollback();
                        return array('success' => false, "errors" => array("unknown" => "Erro ao inserir produto no carrinho"), "http_code" => 400);
                    }
                }
                $this->CI->carrinho_model->trans_commit();
                return array('success' => true, "return" => $retorno, "http_code" => 200);
            } else {
                $this->CI->carrinho_model->trans_rollback();
                return array('success' => false, "errors" => array("unknown" => "Erro ao inserir produto no carrinho"), "http_code" => 400);
            }
        } else
            return $product;
    }

    /**
     * Altera um produto já cadastrado no carrinho
     * @param $data
     * @param $where
     * @return mixed
     */
    public function alterar_produto_carrinho($data, $where)
    {
        $retorno = $this->CI->carrinho_model->alterar_produto_carrinho($data, $where);
        if ($retorno !== FALSE)
            return array('success' => true, "return" => $retorno, "http_code" => 200);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro ao alterar produto no carrinho"), "http_code" => 400);
    }

    /**
     * Deleta um produto já cadastrado no carrinho
     * @param $data
     * @return mixed
     */
    public function deletar_produto_carrinho($data)
    {
        $retorno = $this->CI->carrinho_model->deletar_produto_carrinho($data);
        if ($retorno !== FALSE)
            return array('success' => true, "return" => $retorno, "http_code" => 200);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro ao delertar produto no carrinho"), "http_code" => 400);
    }


    /**
     * Pesquisa um produto já cadastrado no carrinho
     * @param $id_carrinho
     * @param $id_produto
     * @return mixed
     */
    public function pesquisa_produto_carrinho($id_carrinho, $id_produto)
    {
        $retorno = $this->CI->carrinho_model->pesquisa_carrinho($id_carrinho, $id_produto);
        if ($retorno !== FALSE)
            return array('success' => true, "return" => $retorno[0], "http_code" => 200);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro ao delertar produto no carrinho"), "http_code" => 400);
    }

    /**
     * Pesquisa um carrinho pelo id
     * @param $id_carrinho
     * @return mixed
     */
    public function pesquisa_carrinho($id_carrinho)
    {
        $retorno = $this->CI->carrinho_model->pesquisa_carrinho($id_carrinho);
        if ($retorno !== FALSE)
            return array('success' => true, "return" => $retorno, "http_code" => 200);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro ao buscar carrinho ou carrinho não encontrado"), "http_code" => 400);
    }

    /**
     * Busca o valor total do carrinho
     * @param $id_carrinho
     * @return bool
     */
    public function busca_valor_total_carrinho($id_carrinho)
    {
        $retorno = $this->CI->carrinho_model->busca_valor_total_carrinho($id_carrinho);
        if ($retorno !== FALSE)
            return array('success' => true, "return" => $retorno, "http_code" => 200);
        else
            return array('success' => false, "errors" => array("unknown" => "Erro ao buscar carrinho"), "http_code" => 400);
    }

}