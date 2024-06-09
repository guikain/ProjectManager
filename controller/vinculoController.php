<?php
namespace App\controller;

use App\util\Functions as Util;

use App\model\Usuario;
use App\model\Projeto;
use App\model\Vinculo;

use App\dal\UsuarioDao;
use App\dal\ProjetoDao;
use App\dal\VinculoDao;

use App\view\UsuarioView;
use App\view\ProjetoView;
use App\view\VinculoView;

use \Exception;

require_once ('./util/functions.php');  
Util::startSession();


abstract class VinculoController{
    private static $msg = null;

    //para usuarios em geral

    public static function listar() {
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['username'])) {
            
            $config = [
                'isLoggedIn' => isset($_SESSION['username']),
                'isAdmin' => isset($_SESSION['user_group']) && $_SESSION['user_group'] == '2'
            ];
            
            try {
                $userId = $_SESSION['user_id'];
                $result = VinculoDao::listarPorUsuario($userId);

                $vinculos = [];
                foreach ($result as $row) {
                    $vinculo = new Vinculo();
                    $vinculo->iniciar(
                        id: $row['id'],
                        id_user_fk: $row['id_user_fk'],
                        id_projeto_fk: $row['id_projeto_fk'],
                        checkin: $row['checkin'],
                        checkout: $row['checkout'],
                        status: $row['status'],
                        descricao: $row['descricao'],
                        joined: $row['joined'],
                        totalTime: date('H:i:s', $row['totalTime'])
                    );

                    $vinculos[] = $vinculo;
                }

                VinculoView::listar($vinculos, self::$msg, $config);

            } catch (Exception $e) {
                self::$msg = "Ocorreu um erro inesperado: " . $e->getMessage();
                VinculoView::listar([], self::$msg, $config);
            }

        } else {
            header('Location: ?p=e404');
        }
    }

    public static function incluir() {
        if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"]) && isset($_SESSION['user_id'])) {
            
            $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);
            if ($id) {

                $vinculo = new Vinculo();
                $vinculo->iniciar(
                    id_user_fk: $_SESSION['user_id'],
                    id_projeto_fk: $_GET['id'],
                    checkin: date('Y-m-d H:i:s'), 
                    checkout: date('Y-m-d H:i:s'), 
                    status: ProjetoDao::buscar($_GET["id"])->__get('status'),
                    descricao: '',
                    joined: 1
                );

                try {
                    self::$msg = "Vínculo cadastrado com sucesso!";
                    VinculoDao::cadastrar($vinculo);
                    $vinculos = VinculoDao::listar();
                    $config = [
                        'isLoggedIn' => isset($_SESSION['username']), 
                        'isAdmin' => isset($_SESSION['user_group']) && $_SESSION['user_group'] == '2',
                        'user_id' => $_SESSION['user_id']
                    ];
                    self::listar();

                } catch(Exception $e) {
                    self::$msg = $e->getMessage();
                }
            } 
        }else{
            header('Location:./?p=login&npw');
        } 
    }

    public static function entrar() {
        if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"]) && isset($_SESSION['user_id'])) {
            
            $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);
            if ($id) {
                $ovinculo = VinculoDao::buscar($_GET['id']);
                $vinculo = new Vinculo();
                $vinculo->iniciar(
                    id_user_fk: $_SESSION['user_id'],
                    id_projeto_fk: $ovinculo->__get('id_projeto_fk'),
                    checkin: date('Y-m-d H:i:s'), 
                    checkout: date('Y-m-d H:i:s'), 
                    status: $ovinculo->__get('status'),
                    descricao: '',
                    joined: 1
                );
                try {
                    self::$msg = "Vínculo cadastrado com sucesso!";
                    VinculoDao::cadastrar($vinculo);
                    self::listar();

                } catch(Exception $e) {
                    self::$msg = $e->getMessage();
                }
            } 
        }else{
            header('Location:./?p=login&npw');
        } 
    }

    public static function sair(){

        self::$msg = null;
    
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {

            $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);
            if ($id) {
                $vinculo = VinculoDao::buscar($id);
            
                if( isset( $_SESSION['username'] ) && $vinculo && $_SESSION['user_id'] == $vinculo->__get('id_user_fk')){
                    VinculoView::checkout($vinculo, self::$msg);
                } else {
                    self::$msg = "ID inválido.";   
                }
            }else{
                header('Location:./?p=login&npw');
            }   
        };
    
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF(Util::prepararTexto($_POST['csrf_token']))) {
                die('Falha na verificação CSRF.');
            } else {
                $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
                $ovinculo = VinculoDao::buscar($id);
                if ($id && $ovinculo) {
                    $status = Util::prepararTexto($_POST["status"]);
                    $descricao = Util::prepararTexto($_POST["descricao"]);

                    $vinculo = new Vinculo();
                        $vinculo->iniciar(
                            id: $id,
                            id_user_fk: filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT),
                            id_projeto_fk: $ovinculo->__get('id_projeto_fk'),
                            checkin: $ovinculo->__get('checkin'),
                            checkout: date('Y-m-d H:i:s'),
                            status: $status,
                            descricao: $descricao,
                            joined: 0,
                        );
        
                    try {
                        Util::validarStatus($status);
                        Util::validarDescricao($descricao);

                        VinculoDao::cadastrar($vinculo);
                        self::$msg = "Vínculo atualizado com sucesso!";
                        header("Location: ./?p=myprojects");
                        exit();
                    } catch (Exception $e) {
                        self::$msg = $e->getMessage();
                        VinculoView::checkout($vinculo, self::$msg, true);
                    }
                } else {
                    self::$msg = "ID inválido ou vínculo não encontrado.";
                }
            }
        }
    }

    public static function um_listar(){
        
        $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
        Util::checkUserPermission(null, null, $ousuario);

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $config = [
                'isLoggedIn' => isset($_SESSION['username']), 
                'isAdmin' => isset($_SESSION['user_group']) && $_SESSION['user_group'] == '2',
                'user_id' => $_SESSION['user_id']
            ];

            $vinculos = VinculoDao::listarTudo();
            VinculoView::listarTudo($vinculos, self::$msg, $config);
        }else{
            header('Location: ?p=e404');
        }
    }


    public static function um_alteraVinculo() {
        $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
        Util::checkUserPermission(null, null, $ousuario);
    
        $vinculo = null;
        self::$msg = null;
    
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["alt"])) {
            if (isset($_SESSION['username'])) {
                $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
                Util::checkUserPermission(null, null, $ousuario);
    
                $id = filter_var($_GET["alt"], FILTER_VALIDATE_INT);
                if ($id) {
                    try {
                        $vinculo = VinculoDao::buscar($id);
                    } catch (Exception $e) {
                        self::$msg = $e->getMessage();
                    }
                } else {
                    self::$msg = "ID inválido.";
                }
                VinculoView::alterar(self::$msg, $vinculo);
            } else {
                header('Location: ./?p=login&npw');
            }
        }
    
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["descricao"])) {
            if (!isset($_POST['csrf_token']) || !Util::verificarCSRF($_POST['csrf_token'])) {
                die('Falha na verificação CSRF.');
            } else {
                $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
                if ($id) {
                    $vinculo = new Vinculo();
                    $vinculo->iniciar(
                        descricao: Util::prepararTexto($_POST["descricao"]),
                        joined: (isset($_POST['isjoined']) ? '1' : '0'),
                        id: $id
                    );
    
                    try {
                        VinculoDao::alterar($vinculo);
                        self::$msg = "Vinculo atualizado com sucesso!";
                        header("Location: ./?p=vinculos");
                        exit();
                    } catch (Exception $e) {
                        self::$msg = $e->getMessage();
                        // Exibir erro de forma mais amigável
                        VinculoView::alterar(self::$msg, $vinculo, true);
                    }
                } else {
                    self::$msg = "ID inválido.";
                    VinculoView::alterar(self::$msg, null, true);
                }
            }
        }
    }

    public static function um_deletaVinculo(){

        $ousuario = UsuarioDao::buscar($_SESSION['user_id']);
        Util::checkUserPermission(null, null, $ousuario);

        self::$msg = "";
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["del"])) {
            $id = filter_var($_GET["del"], FILTER_VALIDATE_INT);
            if ($id) {
                $ousuario = VinculoDao::buscar($_SESSION['user_id']);
                Util::checkUserPermission(null, null, $ousuario);
                try {
                    VinculoDao::excluir($id);
                    header("Location:./?p=vinculos");   

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
    
}