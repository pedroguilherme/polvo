<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class Autenticacao_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function busca_usuario($auth)
    {
        $this->db->select("u.id");
        $this->db->select("u.nome");
        $this->db->select("u.login");
        $this->db->select("u.senha");
        $this->db->from("usuarios u");
        $this->db->where("u.login", $auth["login"]);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result_array()[0] : false;
    }
}