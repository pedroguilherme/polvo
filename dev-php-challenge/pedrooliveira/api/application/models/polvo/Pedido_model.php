<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class Pedido_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Cadastra um pedido conforme o carrinho
     * @params $data
     * @return mixed
     */
    public function cadastro_pedido($data)
    {
        $retorno = $this->db->insert("pedido", $data);
        return $retorno ? $this->db->insert_id() : false;
    }

    public function pesquisa_pedido($where = array())
    {
        $this->db->select("p.id");
        $this->db->select("p.id_carrinho");
        $this->db->select("p.data");
        $this->db->select("p.valor_total");
        $this->db->from("pedido p");
        if(count($where) > 0)
            $this->db->where($where);

        $result = $this->db->get();

        return $result->num_rows() > 0 ? $result->result_array() : false;
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