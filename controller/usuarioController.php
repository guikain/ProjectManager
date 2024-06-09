<?php
namespace App\controller;

use App\util\Functions as Util;
use App\model\Usuario;
use App\dal\UsuarioDao;
use App\view\UsuarioView;
use App\view\LoginView;
use \Exception;

require_once ('./util/functions.php');  
Util::startSession();


abstract class UsuarioController{
    private static $msg = null;

    public static function cadastrar(){
        if ($_SERVER["REQUEST_METHOD"] == "POST" AND
        isset($_POST["nome"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                die('Falha na verificação CSRF.');
            } else {

                $nome = Util::prepararTexto($_POST["nome"]);
                $sobrenome = Util::prepararTexto($_POST["sobrenome"]);
                $data_nascimento = Util::prepararTexto($_POST["data_nascimento"]);
                $cpf = Util::prepararTexto($_POST["cpf"]);
                $ddd = Util::prepararTexto($_POST["ddd"]);
                $telefone = Util::prepararTexto($_POST["telefone"]);
                $username = Util::prepararTexto($_POST["username"]);
                $pass = Util::prepararTexto($_POST["pass"]);
                $confirmar = Util::prepararTexto($_POST["confirmar"]);

                $usuario = new Usuario();
                    $usuario->iniciar(
                        nome: $nome,
                        sobrenome: $sobrenome,
                        data_nascimento: $data_nascimento,
                        cpf: $cpf,
                        ddd: $ddd,
                        telefone: $telefone,
                        username: $username,
                        pass: $pass,
                    );
    
                if ($pass != $confirmar) {
                    self::$msg = "As senhas digitadas não são iguais.";
                    UsuarioView::cadastrar(self::$msg, $usuario, true);
                    return;
                }
    
                try { 
                    Util::validarSenha($pass);
                    Util::validarSenha($confirmar);
                    UsuarioDao::validarUnicidadeCPF($cpf);
                    UsuarioDao::validarUnicidadeUsername($username);
                } catch (Exception $e) {
                    self::$msg = $e->getMessage();
                    UsuarioView::cadastrar(self::$msg, null, true);
                    return;
                }

                    $usuario->__set('pass', $usuario->__get('pass'));            
                try { 
                    Util::validarNome($_POST["nome"]);
                    Util::validarSobrenome($_POST["sobrenome"]);
                    Util::validarDataNascimento($_POST["data_nascimento"]);
                    Util::validarCPF($_POST["cpf"]);
                    Util::validarDDD($_POST["ddd"]);
                    Util::validarTelefone($_POST["telefone"]);
                    Util::validarUsername($_POST["username"]);
                    Util::validarSenha($_POST["pass"]);
                } catch (Exception $e) {
                    self::$msg = $e->getMessage();
                    UsuarioView::cadastrar(self::$msg, $usuario, true);
                    exit();
                }
                try{
                    self::$msg = "Usuário cadastrado com sucesso!";
                    UsuarioDao::cadastrar($usuario);

                    loginView::login(self::$msg, false);
                    header('Location: ./?p=login');
                } catch(Exception $e) {
                    self::$msg = $e->getMessage();
                }
            }
        }
        UsuarioView::cadastrar(self::$msg);
    }

    public static function Recuperar() {
        $usuario = null;
    
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cpf"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                self::$msg = 'Falha na verificação CSRF.';
                UsuarioView::recuperar(self::$msg, true);
                return;
            } else {
                $cpf = Util::prepararTexto($_POST["cpf"]);
                $data_nascimento = Util::prepararTexto($_POST["data_nascimento"]);
        
                try {
                    Util::validarCPF($cpf);
                    Util::validarDataNascimento($data_nascimento);
                } catch (Exception $e) {
                    self::$msg = $e->getMessage();
                    UsuarioView::recuperar(self::$msg, true);
                    return;
                }
        
                $usuario = UsuarioDao::buscarPorCPF($cpf);
        
                if ($usuario == null) {
                    self::$msg = "Usuário não encontrado.";
                    UsuarioView::recuperar(self::$msg, true);
                    return;
                }
        
                if ($usuario->__get('data_nascimento') != $data_nascimento) {
                    self::$msg = "Data de nascimento incorreta.";
                    UsuarioView::recuperar(self::$msg, true);
                    return;
                }
        
                UsuarioView::alterarSenha(self::$msg, $usuario, false);
                return;
            }
        }
        UsuarioView::recuperar(self::$msg);
    }
    
    public static function profile(){
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if(isset($_SESSION['username'])){
                try{
                    $usuario_by_id = UsuarioDao::buscar($_SESSION['user_id']);
                    UsuarioView::profile($usuario_by_id, self::$msg);
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

                $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
                Util::checkUserPermission(Util::prepararTexto($_POST["id"]), Util::prepararTexto($_POST['pass']), $ousuario);

                
                $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
                if ($id) {
                    $usuario = new Usuario();
                    $usuario->iniciar(
                        id: $id,
                        groupID: UsuarioDao::buscar(Util::prepararTexto($_POST['id']))->__get('groupID'),
                        nome: Util::prepararTexto($_POST["nome"]),
                        sobrenome: Util::prepararTexto($_POST["sobrenome"]),
                        data_nascimento: Util::prepararTexto($_POST["data_nascimento"]),
                        cpf: Util::prepararTexto($_POST["cpf"]),
                        ddd: Util::prepararTexto($_POST["ddd"]),
                        telefone: Util::prepararTexto($_POST["telefone"]),
                        username: Util::prepararTexto($_SESSION["username"]),
                    );
                    try {
                        self::$msg = "Usuário atualizado com sucesso!";
                        UsuarioDao::alterar($usuario);    
                        header("Location:./?p=projects");    
                    } catch(Exception $e) {
                        self::$msg = $e->getMessage();
                    }
                } else {
                    self::$msg = "ID inválido.";
                }
            }
        }


    }

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
                UsuarioView::alterar(self::$msg, $usuario);
            }else{
                $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
            Util::checkUserPermission(null, null, $ousuario);
                UsuarioView::alterar(self::$msg, $usuario);
            }
            
        }
    
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF(Util::prepararTexto($_POST['csrf_token']))) {
                die('Falha na verificação CSRF.');
            } else {
                $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
                if ($id) {
                    $groupID = Util::prepararTexto($_POST["groupID"]);
                    $nome = Util::prepararTexto($_POST["nome"]);
                    $sobrenome = Util::prepararTexto($_POST["sobrenome"]);
                    $ddd = Util::prepararTexto($_POST["ddd"]);
                    $telefone = Util::prepararTexto($_POST["telefone"]);
                    $username = Util::prepararTexto($_POST["username"]);

                    $usuario = new Usuario();
                        $usuario->iniciar(
                            id: $id,
                            groupID: $groupID,
                            nome: $nome,
                            sobrenome: $sobrenome,
                            ddd: $ddd,
                            telefone: $telefone,
                            username: $username,
                        );
        
                    try {
                        Util::validarNome($nome);
                        Util::validarSobrenome($sobrenome);
                        Util::validarDDD($ddd);
                        Util::validarTelefone($telefone);
                        Util::validarUsername($username);
        
                        $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
                        Util::checkUserPermission($id, $usuario->__get('pass'), $ousuario);
        
                        self::$msg = "Usuário atualizado com sucesso!";
                        UsuarioDao::alterar($usuario);
                        header("Location: ./?p=users");
                        exit();
                    } catch (Exception $e) {
                        self::$msg = $e->getMessage();
                        UsuarioView::alterar(self::$msg, $usuario, true);
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
            if ($id && $id != $_SESSION['user_id']) {
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

    public static function um_altPw() {
        $usuario = null;
        self::$msg = "";
    
        if ($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST["pass"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                self::$msg = 'Falha na verificação CSRF.';
                UsuarioView::recuperar(self::$msg, null, true);
                return;
            } else {
                $id = Util::prepararTexto($_POST["id"]);
                $senha = Util::prepararTexto($_POST["pass"]);
                $confirmar = Util::prepararTexto($_POST["confirmar"]);
    
                if ($senha != $confirmar) {
                    self::$msg = "As senhas digitadas não são iguais.";
                    UsuarioView::alterarSenha(self::$msg, null, true);
                    return;
                }
    
                try { 
                    Util::validarSenha($senha);
                    Util::validarSenha($confirmar);
                } catch (Exception $e) {
                    self::$msg = $e->getMessage();
                    UsuarioView::alterarSenha(self::$msg, null, true);
                    return;
                }
    
                $usuario = UsuarioDao::buscar($id);
    
                if ($usuario == null) {
                    header('Location: ./?p=e404');
                    return;
                }
                $usuario->__set('pass', $senha);
                try {
                    UsuarioDao::atualizarSenha($usuario);
                    self::$msg = "Senha alterada com sucesso!";
                    LoginView::login(self::$msg);
                } catch (Exception $e) {
                    self::$msg = $e->getMessage();
                    UsuarioView::alterarSenha(self::$msg, $usuario, true);
                }
            }
        }else{
            header('Location: ./?p=e404');
            return;
        }
    }

}