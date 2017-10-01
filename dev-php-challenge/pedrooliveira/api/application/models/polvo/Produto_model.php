<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class Produto_model extends CI_Model
{
    private $table = 'produto';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Pesquisa todos os produtos ativos
     * @param $id_produto
     * @return mixed
     */
    public function pesquisa_produtos($id_produto = 0)
    {
        $where["p.status"] = 1;
        if($id_produto > 0)
            $where["p.id"] = $id_produto;

        $this->db->select("p.id");
        $this->db->select("p.sku_id");
        $this->db->select("p.nome");
        $this->db->select("p.descricao");
        $this->db->select("p.preco_venda");
        $this->db->from($this->table . " p");
        $this->db->where($where);
        $result = $this->db->get();

        return $result->num_rows() > 0 ? $result->result_array() : false;
    }

    /**
     * Pesquisa produto por ID apenas ativos
     * @param $id_produto
     * @return mixed
     */
    public function pesquisa_produto_por_id($id_produto)
    {
        $retorno = $this->pesquisa_produtos($id_produto);
        if($retorno !== FALSE)
            return $retorno[0];
        else
            return false;
    }

    /**
     * Insere um produto.
     * @param $data
     * @return mixed
     */
    public function inserir_produto($data)
    {
        $retorno = $this->db->insert($this->table, $data);
        return $retorno ? $this->db->insert_id() : false;
    }

    /**
     * Altera um produto ja cadastrado
     * @param $data
     * @param $where
     * @return mixed
     */
    public function alterar_produto($data, $where)
    {
        $this->db->where($where);
        $retorno = $this->db->update($this->table, $data);
        return $retorno ? $retorno : false;
    }

    /**
     * Deleta um produto cadastrado
     * @param $where
     * @return bool
     */
    public function deletar_produto($where)
    {
        return $this->alterar_produto(array("status" => 0, "sku_id" => NULL), $where);
    }

    /**
     * Inicia transação no banco de dados
     */
    public function trans_begin()
    {
        $this->db->trans_begin();
    }

    /**
     * Commita transação no banco de dados
     */
    public function trans_commit()
    {
        $this->db->trans_commit();
    }

    /**
     * Rollback transação no banco de dados
     */
    public function trans_rollback()
    {
        $this->db->trans_rollback();
    }
}