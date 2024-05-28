<?php
namespace App\util;

use Exception;

class Functions{
    public static function prepararTexto($texto){
        return trim(htmlentities($texto));
    }

    public static function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function endSession() {
        session_destroy();
    }

    public static function gerarCSRF() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function verificarCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    //Checar permissões
    public static function checkUserPermission($id = null, $pass=null, $usuario) {
        if(isset($_SESSION['user_group'])){
            $MasterGroup = 2;
            try {
                if ( $_SESSION['user_group'] == $MasterGroup) {
                    return;
                }
                } catch (Exception $e) {
                    die('Acesso negado. a ' . $e);
                }
        }else{
            die('Acesso negado. b');
        }
        

        if (!isset($_SESSION['user_id'])) {
            die('Acesso negado. c');
        }

        try {
            if ($_SESSION['user_id'] != $id) {
                var_dump($_SESSION['user_id'] . " : ". $usuario->__get('pass'));
                die('Acesso negado. d');
            }
        } catch (Exception $e) {
            die('Acesso negado. e');
        }
        if(!password_verify($pass, $usuario->__get('pass'))) {
            header('Location: ./?p='.$_GET['p'].'&alt='.$usuario->__get('id').'&epw');       
        }

    }

}