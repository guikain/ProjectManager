<?php
namespace App\controller;

use App\util\Functions as Util;
use App\model\Projeto;
use App\dal\ProjetoDao;
use App\dal\UsuarioDao;
use App\view\ProjetoView;
use App\view\MasterView;
use \Exception;

require_once ('./util/functions.php');  
Util::startSession();


abstract class ProjetoController{
    private static $msg = null;

    public static function listarProjetos(){
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            if(isset($_GET['join'])){
                $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
                
            }

            $config = [
                'isLoggedIn' => ( isset($_SESSION['username']) ) ? true : false,
                'isAdmin' => ( isset($_SESSION['user_group']) && $_SESSION['user_group'] == '2' ) ? true : false,
                'isJoined' => (true) ? true : false,
            ];

            $projetos = ProjetoDao::listar();
            ProjetoView::listar($projetos, self::$msg, $config);
        }else{
            header('Location: ?p=e404');
        }
    }

    //para usuarios master
    public static function um_cadProj(){
        if ($_SERVER["REQUEST_METHOD"] == "POST" AND
        isset($_POST["nome"])) {
            $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
            Util::checkUserPermission(null, null, $ousuario);

            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                die('Falha na verificação CSRF.');
            } else {

                //TODO CRIAR AQUI OS CONDICIONAIS PARA O FORMULARIO DE CADASTRO

                // SENHA MAIOR QUE 8 CARACTERES CRIAR UM METODO PRA CHAMAR QUE FAÇA O SEGUINTE:
                // if(isset($_POST['pass']) && strlen($_POST['pass']) < 8){
                //     header('Location: ./?p=cadusr' . '&ipw');     
                // }

                $projeto = new Projeto();
                $projeto->iniciar(
                    nome: Util::prepararTexto($_POST["nome"]),
                    prioridade: Util::prepararTexto($_POST["prioridade"]),
                    dificuldade: Util::prepararTexto($_POST["dificuldade"]),
                    data_inicio: date('Y-m-d', timestamp:time()),
                    prazo: Util::prepararTexto($_POST["prazo"]),	
                    data_fim: '-',
                    status: Util::prepararTexto($_POST["status"])
                );

                try{
                    self::$msg = "Projeto criado com sucesso!";
                    ProjetoDao::cadastrar($projeto);
                    header('Location: ./?p=projects');
                }catch(Exception $e){
                    self::$msg = $e->getMessage();
                }
            }
        }
        ProjetoView::cadastrar(self::$msg);
    }

    public static function um_altProj(){

        $projeto = null;
        self::$msg = null;
    
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["alt"])) {
            
            if( isset( $_SESSION['username'] ) ){
                $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
                Util::checkUserPermission(null, null, $ousuario);

                $id = filter_var($_GET["alt"], FILTER_VALIDATE_INT);
                if ($id) {
                    try {
                        $projeto = ProjetoDao::buscar($id);
                    } catch(Exception $e) {
                        self::$msg = $e->getMessage();
                    }
                } else {
                    self::$msg = "ID inválido.";
                    
                }
                 ProjetoView::alterar($projeto, self::$msg);
            }else{
                header('Location:./?p=login&npw');
            }   
        };
    
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                die('Falha na verificação CSRF.');
            } else {
                $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
                if ($id) {
                    $projeto = new Projeto();
                    $projeto->iniciar(
                        id: $id,
                        nome: Util::prepararTexto($_POST["nome"]),
                        prioridade: Util::prepararTexto($_POST["prioridade"]),
                        dificuldade: Util::prepararTexto($_POST["dificuldade"]),
                        data_inicio: Util::prepararTexto($_POST["data_inicio"]),
                        prazo: Util::prepararTexto($_POST["prazo"]),
                        data_fim: Util::prepararTexto($_POST["data_fim"]),
                        status: Util::prepararTexto($_POST["status"]),
                    );
                        try {
                            self::$msg = "Projeto atualizado com sucesso!";
                            ProjetoDao::alterar($projeto);    
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

    public static function um_delProj(){
        self::$msg = "";
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["del"])) {
            $id = filter_var($_GET["del"], FILTER_VALIDATE_INT);
            if ($id) {
                $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
                Util::checkUserPermission(null, null, $ousuario);
                try {
                    ProjetoDao::excluir($id);
                    header("Location:./?p=projects");   

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