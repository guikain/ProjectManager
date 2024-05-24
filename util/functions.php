<?php
namespace App\util;

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

}