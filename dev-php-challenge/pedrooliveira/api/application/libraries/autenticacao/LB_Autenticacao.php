<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . '/libs_externas/php-jwt-master/vendor/autoload.php';
use Firebase\JWT\JWT;

/**
 * @author    Pedro Guilherme Favaro de Oliveira
 */
class LB_Autenticacao
{

    private $CI;
    private $keyPolvo = "polvo_challenge";
    private $token = "";

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('autenticacao/Autenticacao_model', 'model_autenticacao', TRUE);
    }

    public function gera_token($auth)
    {
        $retorno = $this->CI->model_autenticacao->busca_usuario($auth);
        if ($retorno !== FALSE && password_verify($auth["senha"], $retorno["senha"])) {
            unset($retorno["senha"]);
            $token = array(
                "auth" => $retorno,
                "ipGenerator" => $_SERVER["REMOTE_ADDR"],
                "iat" => strtotime('now'),
                "nbf" => strtotime('now'),
                "exp" => strtotime('+30 minutes')
            );
            $this->token = JWT::encode($token, $this->keyPolvo);
            return array('success' => true, "return" => array("token"=>$this->token), "http_code" => 200);
        } else
            return array('success' => false, "errors" => array("unknown" => "Login ou senha inválidos"), "http_code" => 403);
    }

    public function valida_token($token)
    {
        try {
            $decoded = JWT::decode($token, $this->keyPolvo, array('HS256'));
            if ($_SERVER["REMOTE_ADDR"] == $decoded->ipGenerator) {
                    return array('success' => true, "return" => array("token"=>$decoded), "http_code" => 203);
            }
        } catch (Exception $e) {
            return array('success' => false, "errors" => array("unknown" => "Token inválido ou expirado"), "http_code" => 403);
        }
    }
}