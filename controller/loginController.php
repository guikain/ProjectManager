<?php

namespace App\controller;

use App\util\Functions as Util;
use App\dal\UsuarioDao;
use \Exception;
use App\view\LoginView;

Util::startSession();

class LoginController {
    public static function login() {

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            
            if(isset($_GET['out'])){
                Util::endSession();
                header("Location: ./?p=login");
                exit();
            }

            if(isset($_SESSION["username"])){
                header("Location: ./?p=projects");
                exit();
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {

            $username = Util::prepararTexto($_POST["username"]);
            $password = Util::prepararTexto(trim($_POST["password"]));

            try {
                Util::validarUsername($username);
                Util::validarSenha($password);

                $user = UsuarioDao::buscarPorUsername($username);
                if ($user) {
                    if (password_verify($password, $user->__get('pass'))) {
                        Util::startSession();
                        $_SESSION['user_id'] = $user->__get('id');
                        $_SESSION['username'] = $user->__get('username');
                        $_SESSION['user_group'] = $user->__get('groupID');

                        $trackingId = bin2hex(random_bytes(16));
                        setcookie('tid', $trackingId, time() + (86400 * 30), "/");

                        header("Location: ./?p=projects");
                        exit();
                    } else {
                        $msg = "Credenciais inválidas.";
                        LoginView::login($msg, true);
                        exit();
                    }
                } else {
                    $msg = "Usuário não encontrado.";
                    LoginView::login($msg, true);
                    exit();
                }
            } catch (Exception $e) {
                $msg = $e->getMessage();
                LoginView::login($msg, true);
                exit();
            }
        }
        LoginView::login($msg ?? null);
    }
}

?>
