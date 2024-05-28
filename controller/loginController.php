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
            $password = trim($_POST["password"]);
            
            try {
                $user = UsuarioDao::buscarPorUsername($username);
                
                if ($user) {
                    if (password_verify($password, $user->__get('pass'))) {
                        $_SESSION['user_id'] = $user->__get('id');
                        $_SESSION['username'] = $user->__get('username');
                        $_SESSION['user_group'] = $user->__get('groupID');

                        header("Location: ./?p=projects");
                        exit();
                    } else {
                        var_dump($user);
                        var_dump(password_verify($password, $user->__get('pass')));
                        $msg = "Credenciais inválidas.";
                    }
                }
            } catch (Exception $e) {
                $msg = $e->getMessage();
            }
        }
        LoginView::login($msg ?? null);
    }
}

?>
