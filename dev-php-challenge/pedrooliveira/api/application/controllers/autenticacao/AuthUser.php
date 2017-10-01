<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class AuthUser extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('autenticacao/LB_Autenticacao');
        $this->load->library('form_validation');
    }

    public function gera_token_post()
    {
        $this->form_validation->set_data((array)json_decode($this->input->raw_input_stream));
        $this->form_validation->set_rules('login', 'LOGIN', 'trim|required');
        $this->form_validation->set_rules('senha', 'SENHA', 'trim|required');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else
            $retorno = $this->lb_autenticacao->gera_token($this->form_validation->validation_data);

        $this->response($retorno, $retorno["http_code"]);
    }

    public function valida_token_post()
    {
        $this->form_validation->set_data((array)json_decode($this->input->raw_input_stream));
        $this->form_validation->set_rules('token', 'TOKEN', 'trim|required');

        if ($this->form_validation->run() == FALSE)
            $retorno = array('success' => false, "errors" => $this->form_validation->error_array(), "http_code" => 400);
        else
            $retorno = $this->lb_autenticacao->valida_token($this->form_validation->validation_data["token"]);

        $this->response($retorno, $retorno["http_code"]);
    }

}
