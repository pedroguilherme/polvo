<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class Carrinho_model extends CI_Model
{
    private $table = 'carrinho';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Insere um produto em um carrinho.
     * @param $data
     * @return mixed
     */
    public function inserir_produto_carrinho($data)
    {
        $retorno = $this->db->insert($this->table, $data);
        return $retorno ? $this->db->insert_id() : false;
    }

    /**
     * Altera um produto já cadastrado no carrinho
     * @param $data
     * @param $where
     * @return mixed
     */
    public function alterar_produto_carrinho($data, $where)
    {
        $this->db->where($where);
        $retorno = $this->db->update($this->table, $data);

        return $retorno ? $retorno : false;
    }

    /**
     * Deleta um produto cadastrado no carrinho
     * @param $where
     * @return bool
     */
    public function deletar_produto_carrinho($where)
    {
        $this->db->where($where);
        $retorno = $this->db->delete($this->table);
        return $retorno ? true : false;
    }

    /**
     * Pesquisa um carrinho pelo id
     * @param $id_carrinho
     * @param $id_produto
     * @return mixed
     */
    public function pesquisa_carrinho($id_carrinho, $id_produto = 0)
    {
        $where["c.id_carrinho"] = $id_carrinho;
        if($id_produto > 0)
            $where["c.id_produto"] = $id_produto;

        $this->db->select("c.id");
        $this->db->select("c.id_carrinho");
        $this->db->select("c.id_produto");
        $this->db->select("p.sku_id");
        $this->db->select("p.nome");
        $this->db->select("c.quantidade");
        $this->db->select("p.preco_venda");
        $this->db->select("(p.preco_venda*c.quantidade) as valor_total_produto");
        $this->db->from($this->table . " c");
        $this->db->join('produto p', 'p.id = c.id_produto AND p.status = 1');
        $this->db->where($where);
        $result = $this->db->get();

        return $result->num_rows() > 0 ? $result->result_array() : false;
    }

    /**
     * Busca o valor total do carrinho
     * @param $id_carrinho
     * @return bool
     */
    public function busca_valor_total_carrinho($id_carrinho)
    {
        $this->db->select("SUM(p.preco_venda*c.quantidade) as valor_total_carrinho");
        $this->db->from($this->table . " c");
        $this->db->join('produto p', 'p.id = c.id_produto AND p.status = 1');
        $this->db->where(array(
            "id_carrinho" => $id_carrinho
        ));
        $result = $this->db->get();
        return $result->num_rows() > 0 ? $result->result_array()[0] : false;
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