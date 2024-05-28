<?php
namespace App;
use App\controller\UsuarioController as usuario;
use App\controller\ProjetoController as projeto;
use App\controller\LoginController as login;
use App\util\Functions as Util;
use App\dal\UsuarioDao;
use App\dal\ProjetoDao;

require_once("./autoload.php");
require_once ('./util/functions.php');  

Util::startSession();
Util::gerarCSRF();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projetos</title>
    <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
    <header>
        <nav>
             <?php 
                if (!isset($_SESSION['username'])) {
                    require_once("./initialmenu.php");
                } else {
                    if( isset($_SESSION['username']) && UsuarioDao::buscarPorUsername($_SESSION['username'])->__get('groupID') == 2 ) {
                        require_once("./mastermenu.php");
                    }else{
                        require_once("./menu.php");
                    }
                    
                }
            ?>
        </nav>
    </header>
    <main>
        <?php
        $page = $_GET["p"] ?? "home";
        match($page){

        //acesso publico
            //home
            "home" => require_once("./view/home.php"),

            //login
            "login" => login::login(),
            "cadusr" => usuario::cadastrar(),
            #"recusr" => usuario::recuperar(),

            //projetos            
            "projects" => Projeto::listarProjetos(),
            #"myprojects" => Participa::listarProjetos(),

            //usuarios
            "profile" => usuario::profile(),
        
        //acesso master
            //projetos
            "cadproj" => Projeto::um_cadProj(),
            "altproj" => Projeto::um_altProj(),
            "delproj" => Projeto::um_delProj(),
            //usuarios
            "users" => usuario::um_listar(),
            "altusr" => usuario::um_alterar(),
            "delusr" => usuario::um_deletar(),

            default => require_once("./view/404.php")
        };
        ?>
    </main>
    <footer>
        <small>
            Copyright &copy; - <?= date("Y")?>
        </small>
    </footer>
</body>
</html>
