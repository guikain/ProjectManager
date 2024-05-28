<?php
namespace App\controller;

use App\util\Functions as Util;
use App\model\Usuario;
use App\dal\UsuarioDao;
use App\view\UsuarioView;
use App\view\LoginView;
use App\view\MasterView;
use \Exception;

require_once ('./util/functions.php');  
Util::startSession();


abstract class UsuarioController{
    private static $msg = null;

    //para usuarios em geral
    public static function cadastrar(){
        if ($_SERVER["REQUEST_METHOD"] == "POST" AND
        isset($_POST["nome"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                die('Falha na verificação CSRF.');
            } else {

                //TODO CRIAR AQUI OS CONDICIONAIS PARA O FORMULARIO DE CADASTRO

                // SENHA MAIOR QUE 8 CARACTERES
                // if(isset($_POST['pass']) && strlen($_POST['pass']) < 8){
                //     header('Location: ./?p=cadusr' . '&ipw');     
                // }

                $usuario = new Usuario();
                $usuario->iniciar(
                    nome: Util::prepararTexto($_POST["nome"]),
                    sobrenome: Util::prepararTexto($_POST["sobrenome"]),
                    data_nascimento: Util::prepararTexto($_POST["data_nascimento"]),
                    cpf: Util::prepararTexto($_POST["cpf"]),
                    ddd: Util::prepararTexto($_POST["ddd"]),
                    telefone: Util::prepararTexto($_POST["telefone"]),
                    username: Util::prepararTexto($_POST["username"]),
                    pass: ($_POST["pass"]),
                );
                
                $usuario->__set('pass', $usuario->__get('pass'));            

                try{
                    self::$msg = "Usuário cadastrado com sucesso!";
                    UsuarioDao::cadastrar($usuario);

                    //TODO entender como fazer isso sem get.
                    loginView::login(self::$msg, false);
                    header('Location: ./?p=login');
                    

                }catch(Exception $e){
                    self::$msg = $e->getMessage();
                }
            }
        }
        UsuarioView::cadastrar(self::$msg);
    }
    
    public static function profile(){
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if(isset($_SESSION['username'])){
                try{
                    $usuario_by_id = UsuarioDao::buscar($_SESSION['user_id']);
                    $usuario_by_username = UsuarioDao::buscarPorUsername($_SESSION['username']);
                    if($usuario_by_id == $usuario_by_username){
                        UsuarioView::profile($usuario_by_id, self::$msg);
                    }else{
                        header('Location: ?p=e404');
                    }
                }catch(Exception $e) {
                    self::$msg = $e->getMessage();
                }
                
            }else{
            header('Location: ?p=e404');
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                die('Falha na verificação CSRF.');
            } else {
                $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
                if ($id) {
                    $usuario = new Usuario();
                    $usuario->iniciar(
                        id: $id,
                        groupID: UsuarioDao::buscar($_POST['id'])->__get('groupID'),
                        nome: Util::prepararTexto($_POST["nome"]),
                        sobrenome: Util::prepararTexto($_POST["sobrenome"]),
                        data_nascimento: Util::prepararTexto($_POST["data_nascimento"]),
                        cpf: Util::prepararTexto($_POST["cpf"]),
                        ddd: Util::prepararTexto($_POST["ddd"]),
                        telefone: Util::prepararTexto($_POST["telefone"]),
                        username: Util::prepararTexto($_POST["username"]),
                    );
                    $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
                    Util::checkUserPermission($_POST["id"], $_POST['pass'], $ousuario);
    
                    try {
                        self::$msg = "Usuário atualizado com sucesso!";
                        UsuarioDao::alterar($usuario);    
                        header("Location:./");    
                    } catch(Exception $e) {
                        self::$msg = $e->getMessage();
                    }
                } else {
                    self::$msg = "ID inválido.";
                }
            }
        }


    }
    //para usuarios master
    public static function um_listar(){
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["p"]) && $_GET["p"] == 'users' && isset($_SESSION['username'])) {
            
            $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
            Util::checkUserPermission(null, null, $ousuario);
            $usuarios = UsuarioDao::listar();
            UsuarioView::listar($usuarios, self::$msg);
        }else{
            header('Location: ?p=e404');
        }
    }

    public static function um_alterar(){

        $usuario = null;
        self::$msg = null;
    
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["alt"])) {
            $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
            Util::checkUserPermission(null, null, $ousuario);
            $id = filter_var($_GET["alt"], FILTER_VALIDATE_INT);
            if ($id) {
                try {
                    $usuario = UsuarioDao::buscar($id);
                } catch(Exception $e) {
                    self::$msg = $e->getMessage();
                }
            } else {
                self::$msg = "ID inválido.";
                
            }

            if($_GET["alt"] == $_SESSION['user_id']){
                UsuarioView::alterar($usuario, self::$msg);
            }else{
                $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
            Util::checkUserPermission(null, null, $ousuario);
                UsuarioView::alterar($usuario, self::$msg);
            }
            
        }
    
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                die('Falha na verificação CSRF.');
            } else {
                $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
                if ($id) {
                    $usuario = new Usuario();
                    $usuario->iniciar(
                        id: $id,
                        groupID: Util::prepararTexto($_POST["groupID"]),
                        nome: Util::prepararTexto($_POST["nome"]),
                        sobrenome: Util::prepararTexto($_POST["sobrenome"]),
                        data_nascimento: Util::prepararTexto($_POST["data_nascimento"]),
                        cpf: Util::prepararTexto($_POST["cpf"]),
                        ddd: Util::prepararTexto($_POST["ddd"]),
                        telefone: Util::prepararTexto($_POST["telefone"]),
                        username: Util::prepararTexto($_POST["username"]),
                    );
                    $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
                    Util::checkUserPermission($_POST["id"], $usuario->__get('pass'),$ousuario);
    
                    try {
                        self::$msg = "Usuário atualizado com sucesso!";
                        UsuarioDao::alterar($usuario);    
                        header("Location:./?p=users");    
                    } catch(Exception $e) {
                        self::$msg = $e->getMessage();
                    }
                } else {
                    self::$msg = "ID inválido.";
                }
            }
        }
    }

    public static function um_deletar(){
        self::$msg = "";
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["del"])) {
            $id = filter_var($_GET["del"], FILTER_VALIDATE_INT);
            if ($id) {
                $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
                Util::checkUserPermission(null, null, $ousuario);
                try {
                    UsuarioDao::excluir($id);
                    header("Location:./?p=users");   

                } catch(Exception $e) {
                    self::$msg = $e->getMessage();
                }
            } else {
                self::$msg = "ID inválido.";
            }
       }else{
        header('Location: ?p=e404');
        }
    }

    /*public static function Recuperar(){
        $usuario = null;
        self::$msg = "";

        if(){
            $id = filter_var($_GET["rec"], FILTER_VALIDATE_INT);
            if ($id) {
                try {
                    $usuario = UsuarioDao::buscar($id);
                } catch(Exception $e) {
                    self::$msg = $e->getMessage();
                }
            } else {
                self::$msg = "ID inválido.";
            }
        }
*/
}