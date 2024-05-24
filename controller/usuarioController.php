<?php
namespace App\controller;

use App\util\Functions as Util;
use App\model\Usuario;
use App\dal\UsuarioDao;
use App\view\UsuarioView;
use App\view\MasterView;
use \Exception;

require_once ('./util/functions.php');  
Util::startSession();


abstract class UsuarioController{
    private static $msg = null;

    //Checar permissões
    private static function checkUserPermission($id = null, $pass=null) {
        if(isset($_SESSION['username']) && isset($_SESSION['user_id'])){
            $MasterGroup = 2;
            try {
                    $usuario_por_username = UsuarioDao::buscarPorUsername($_SESSION['username']);
                    $usuario_por_id = UsuarioDao::buscar($_SESSION['user_id']);
                    $username_group = $usuario_por_username->__get('groupID');
                    $id_group = $usuario_por_id->__get('groupID');
    
                    if ( $id_group == $MasterGroup && $username_group == $MasterGroup) {
                        return;
                    }
                } catch (Exception $e) {
                    die('Acesso negado. a');
                }
        }else{
            die('Acesso negado. b');
        }
        

        if (!isset($_SESSION['user_id'])) {
            die('Acesso negado. c');
        }

        try {
            $logado = UsuarioDao::buscarPorUsername($_SESSION['username']);

            if ($_SESSION['user_id'] != $id) {
                var_dump($_SESSION['user_id'] . " : ". $logado->__get('pass'));
                die('Acesso negado. d');
            }
        } catch (Exception $e) {
            die('Acesso negado. e');
        }
        if(!password_verify($pass, $logado->__get('pass'))) {
            header('Location: ./?p='.$_GET['p'].'&alt='.$logado->__get('id').'&epw');       
        }

    }
    //para usuarios em geral
    public static function cadastrar(){
        if ($_SERVER["REQUEST_METHOD"] == "POST" AND
        isset($_POST["nome"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                die('Falha na verificação CSRF.');
            } else {

                if(isset($_POST['pass']) && strlen($_POST['pass']) < 8){
                    die(header('Location: ./?p=cadusr' . '&ipw'));     
                }

                $usuario = new Usuario();
                $usuario->iniciar(
                    nome: Util::prepararTexto($_POST["nome"]),
                    sobrenome: Util::prepararTexto($_POST["sobrenome"]),
                    data_nascimento: Util::prepararTexto($_POST["data_nascimento"]),
                    cpf: Util::prepararTexto($_POST["cpf"]),
                    ddd: Util::prepararTexto($_POST["ddd"]),
                    telefone: Util::prepararTexto($_POST["telefone"]),
                    username: Util::prepararTexto($_POST["username"]),
                    pass: Util::prepararTexto($_POST["pass"]),
                );
                $usuario->__set('pass', $usuario->__get('pass'));

                try{
                    self::$msg = "Usuário cadastrado com sucesso!";
                    UsuarioDao::cadastrar($usuario);
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

                    self::checkUserPermission($_POST["id"], $_POST['pass']);
    
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
    public static function um_users(){
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["p"]) && $_GET["p"] == 'listusr' && isset($_SESSION['username'])) {
            self::checkUserPermission();
            $usuarios = UsuarioDao::listar();
            MasterView::listar($usuarios, self::$msg);
        }else{
            header('Location: ?p=e404');
        }
    }

    public static function um_alterar(){

        $usuario = null;
        self::$msg = null;
    
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["alt"])) {
            self::checkUserPermission();
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
                MasterView::alterar($usuario, self::$msg);
            }else{
                self::checkUserPermission();
                MasterView::alterar($usuario, self::$msg);
            }
            
        }else{
            header('Location: ?p=e404');
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
                        pass: Util::prepararTexto($_POST["pass"]),
                    );

                    self::checkUserPermission($_POST["id"], $usuario->__get('pass'));
    
                    try {
                        self::$msg = "Usuário atualizado com sucesso!";
                        UsuarioDao::alterar($usuario);    
                        header("Location:./?p=listusr");    
                    } catch(Exception $e) {
                        self::$msg = $e->getMessage();
                    }
                } else {
                    self::$msg = "ID inválido.";
                }
            }
        }
    }

    public static function um_deluser(){
        self::$msg = "";
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["del"])) {
            $id = filter_var($_GET["del"], FILTER_VALIDATE_INT);
            if ($id) {
                self::checkUserPermission();
                try {
                    UsuarioDao::excluir($id);
                    header("Location:./?p=listusr");   

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