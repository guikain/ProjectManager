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

        if ($id == null && $pass == null) {
            if(isset($_SESSION['user_group'])){
                $MasterGroup = 2;
                try {
                    if ( $_SESSION['user_group'] == $MasterGroup) {
                        return;
                    }
                    } catch (Exception $e) {
                        header('Location: ?p=e404');
                    }
            }else{
                header('Location: ?p=e404');
            }
        } 
        
    
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?p=e404');
        }

        try {
            if ($_SESSION['user_id'] != $id) {
                $_SESSION['user_id'] . " : ". $usuario->__get('pass');
                header('Location: ?p=e404');
            }
        } catch (Exception $e) {
            header('Location: ?p=e404');
        }
        if($pass && !password_verify($pass, $usuario->__get('pass'))) {
            header('Location: ./?p='.$_GET['p'].'&alt='.$usuario->__get('id').'&epw'); 
            exit;      
        }
        if ($pass == null && $_SESSION['user_group'] != $MasterGroup){
            header('Location: ?p=e404');
        }
        if($pass && password_verify($pass, $usuario->__get('pass'))){
            return;
        }else{
            header('Location: ?p=e404');
        }
    }

    public static function validarNome($nome) {
        if (empty($nome)) {
            throw new Exception("O campo nome é obrigatório.");
        }
        if (!preg_match("/^[a-zA-Z ]+$/", $nome)) {
            throw new Exception("O nome deve conter apenas letras e espaços.");
        }
    }

    public static function validarSobrenome($sobrenome) {
        if (empty($sobrenome)) {
            throw new Exception("O campo sobrenome é obrigatório.");
        }
        if (!preg_match("/^[a-zA-Z ]+$/", $sobrenome)) {
            throw new Exception("O sobrenome deve conter apenas letras e espaços.");
        }
    }

    public static function validarDataNascimento($data_nascimento) {
        if (empty($data_nascimento)) {
            throw new Exception("O campo data de nascimento é obrigatório.");
        }
        $data_formatada = date('Y-m-d', strtotime($data_nascimento));
        if ($data_formatada != $data_nascimento) {
            throw new Exception("A data de nascimento deve estar no formato AAAA-MM-DD.");
        }
    }

    public static function validarCPF($cpf) {
        if (empty($cpf)) {
            throw new Exception("O campo CPF é obrigatório.");
        }
        if (!preg_match("/^\d{11}$/", $cpf)) {
            throw new Exception("O CPF deve conter exatamente 11 dígitos.");
        }
    }

    public static function validarDDD($ddd) {
        if (empty($ddd)) {
            throw new Exception("O campo DDD é obrigatório.");
        }
        if (!preg_match("/^\d{2}$/", $ddd)) {
            throw new Exception("O DDD deve conter exatamente 2 dígitos.");
        }
    }

    public static function validarTelefone($telefone) {
        if (empty($telefone)) {
            throw new Exception("O campo telefone é obrigatório.");
        }
        if (!preg_match("/^\d{8,9}$/", $telefone)) {
            throw new Exception("O telefone deve conter entre 8 e 9 dígitos.");
        }
    }

    public static function validarUsername($username) {
        if (empty($username)) {
            throw new Exception("O campo username é obrigatório.");
        }
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
            throw new Exception("O username deve conter apenas letras, números e underscores.");
        }
    }

    public static function validarSenha($senha) {
        if (empty($senha)) {
            throw new Exception("O campo senha é obrigatório.");
        }
        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter pelo menos 6 caracteres.");
        }
    }

    public static function validarRecuperacao($cpf, $data_nascimento){
        if (empty($cpf)) {
            throw new Exception("O campo CPF é obrigatório.");
        }
        if (empty($data_nascimento)) {
            throw new Exception("O campo data de nascimento é obrigatório.");
        }
        if (!preg_match("/^\d{11}$/", $cpf)) {
            throw new Exception("O CPF deve conter exatamente 11 dígitos.");
        }
        $data_formatada = date('Y-m-d', strtotime($data_nascimento));
        if ($data_formatada!= $data_nascimento) {
            throw new Exception("A data de nascimento deve estar no formato AAAA-MM-DD.");
        }
    }
    public static function validarPrioridade($prioridade) {
        if (empty($prioridade)) {
            throw new Exception("O campo prioridade é obrigatório.");
        }
        if (!preg_match("/^[1-5]$/", $prioridade)) {
            throw new Exception("A prioridade deve ser um valor entre 1 e 5.");
        }
    }

    public static function validarDificuldade($dificuldade) {
        if (empty($dificuldade)) {
            throw new Exception("O campo dificuldade é obrigatório.");
        }
        if (!preg_match("/^[1-5]$/", $dificuldade)) {
            throw new Exception("A dificuldade deve ser um valor entre 1 e 5.");
        }
    }

    public static function validarPrazo($prazo) {
        if (empty($prazo)) {
            throw new Exception("O campo prazo é obrigatório.");
        }
        $data_formatada = date('Y-m-d', strtotime($prazo));
        if ($data_formatada != $prazo) {
            throw new Exception("O prazo deve estar no formato AAAA-MM-DD.");
        }
    }

    public static function validarStatus($status) {
        if (empty($status)) {
            throw new Exception("O campo status é obrigatório.");
        }
        if (strlen($status) > 30) {
            throw new Exception("O status deve ter no máximo 30 caracteres.");
        }
    }

    public static function validarDescricao($status) {
        if (empty($status)) {
            throw new Exception("O campo status é obrigatório.");
        }
        if (strlen($status) > 255) {
            throw new Exception("O status deve ter no máximo 255 caracteres.");
        }
    }

}