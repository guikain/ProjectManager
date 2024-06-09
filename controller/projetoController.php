<?php
namespace App\controller;

use App\util\Functions as Util;
use App\model\Projeto;
use App\dal\ProjetoDao;
use App\dal\VinculoDao;
use App\dal\UsuarioDao;
use App\view\ProjetoView;
use \Exception;

require_once ('./util/functions.php');  
Util::startSession();


abstract class ProjetoController{
    private static $msg = null;

    public static function listar(){
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            $config = [
                'isLoggedIn' => ( isset($_SESSION['username']) ) ? true : false,
                'isAdmin' => ( isset($_SESSION['user_group']) && $_SESSION['user_group'] == '2' ) ? true : false,
                'user_id' => (isset($_SESSION['user_id']))? $_SESSION['user_id']: '',
            ];

            $projetos = ProjetoDao::listar();
            ProjetoView::listar($projetos, self::$msg, true, $config);
        }else{
            header('Location: ?p=e404');
        }
    }

    //para usuarios master
    public static function um_cadProj(){
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['username'])) {
            
            $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
            Util::checkUserPermission(null, null, $ousuario);
        }
        if(!isset($_SESSION['username'])){
            header('Location: ?p=e404');
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"])) {
            $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
            Util::checkUserPermission(null, null, $ousuario);
        
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                die('Falha na verificação CSRF.');
            } else {
                $nome = Util::prepararTexto($_POST["nome"]);
                $prioridade = Util::prepararTexto($_POST["prioridade"]);
                $dificuldade = Util::prepararTexto($_POST["dificuldade"]);
                $prazo = Util::prepararTexto($_POST["prazo"]);
                $status = Util::prepararTexto($_POST["status"]);

                $projeto = new Projeto();
                    $projeto->iniciar(
                        nome: $nome,
                        prioridade: $prioridade,
                        dificuldade: $dificuldade,
                        data_inicio: date('Y-m-d'),
                        prazo: $prazo,    
                        data_fim: '-',
                        status: $status
                    );
        
                try {
                    Util::validarPrioridade($prioridade);
                    Util::validarDificuldade($dificuldade);
                    Util::validarPrazo($prazo);
                    Util::validarStatus($status);
        
                    self::$msg = "Projeto criado com sucesso!";
                    ProjetoDao::cadastrar($projeto);
                    header('Location: ./?p=projects');
                    exit();
                } catch (Exception $e) {
                    self::$msg = $e->getMessage();
                    ProjetoView::cadastrar(self::$msg, $projeto, true);
                    return;
                }
            }
        }
        ProjetoView::cadastrar(self::$msg, null, null);
    }

    public static function um_altProj(){

        $projeto = null;
        self::$msg = null;
    
        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
            Util::checkUserPermission(null, null, $ousuario);
            
            if( isset( $_SESSION['username'] ) ){
                
                if (isset($_GET["alt"])) $id = filter_var($_GET["alt"], FILTER_VALIDATE_INT);
                if (isset($_GET["vinc"])) $id = filter_var($_GET["vinc"], FILTER_VALIDATE_INT);
                
                if ($id) {
                    try {

                        if (isset($_GET["alt"])) $projeto = ProjetoDao::buscar($id); 
                        if (isset($_GET["vinc"])) $projeto = ProjetoDao::buscar(VinculoDao::buscar($id)->__get('id_projeto_fk')); 
                    
                    } catch(Exception $e) {
                        self::$msg = $e->getMessage();
                    }
                } else {
                    self::$msg = "ID inválido.";
                    
                }
                ProjetoView::alterar(self::$msg, $projeto);
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
                    $nome = Util::prepararTexto($_POST["nome"]);
                    $prioridade = Util::prepararTexto($_POST["prioridade"]);
                    $dificuldade = Util::prepararTexto($_POST["dificuldade"]);
                    $prazo = Util::prepararTexto($_POST["prazo"]);
                    $data_fim = Util::prepararTexto($_POST["data_fim"]);
                    $status = Util::prepararTexto($_POST["status"]);

                    $projeto = new Projeto();
                        $projeto->iniciar(
                            nome: $nome,
                            prioridade: $prioridade,
                            dificuldade: $dificuldade,
                            prazo: $prazo,
                            data_fim: $data_fim,
                            status: $status,
                            id: $id,
                        );
        
                    try {
                        Util::validarNome($nome);
                        Util::validarPrioridade($prioridade);
                        Util::validarDificuldade($dificuldade);
                        Util::validarPrazo($prazo);
                        Util::validarStatus($status);
    
                        self::$msg = "Projeto atualizado com sucesso!";
                        ProjetoDao::alterar($projeto);
                        header("Location: ./?p=projects");
                        exit();
                    } catch (Exception $e) {
                        self::$msg = $e->getMessage();
                        ProjetoView::alterar(self::$msg, $projeto, true);
                    }
                } else {
                    self::$msg = "ID inválido.";
                    
                }
            }
        }
    }

    public static function um_delProj() {
        self::$msg = "";
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["del"])) {
            $id = filter_var($_GET["del"], FILTER_VALIDATE_INT);
            if ($id) {
                $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
                Util::checkUserPermission(null, null, $ousuario);

                try {
                    $vinculos = VinculoDao::contarVinculosPorProjeto($id);
                    if ($vinculos > 0) {
                        self::$msg = "Não é possível excluir o projeto. Existem vínculos associados.";
                        self::listar();

                    } else {
                        ProjetoDao::excluir($id);
                        self::$msg = "Projeto excluído com sucesso.";
                        header("Location: ./?p=projects");
                        exit();
                    }
                } catch (Exception $e) {
                    self::$msg = $e->getMessage();
                }
            } else {
                self::$msg = "ID inválido.";
            }
        } else {
            header('Location: ?p=e404');
        }
    }
}