<?php
namespace App\dal;
use App\model\Vinculo;
use \Exception;
use \PDOException;
use \PDO;

abstract class VinculoDao{

    public static function cadastrar(Vinculo $vinculo){
        try{
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("INSERT INTO vinculo VALUES (null, ?, ?, ?, ?, ?, ?, ?)");
            
            $sql-> execute([$vinculo->__get("id_user_fk"), 
                            $vinculo->__get("id_projeto_fk"),
                            $vinculo->__get("checkin"),
                            $vinculo->__get("checkout"),
                            $vinculo->__get('status'),
                            $vinculo->__get('descricao'),
                            $vinculo->__get('joined')
                        ]);
        }catch(PDOException $e){
            throw new Exception("Erro ao salvar no banco de dados". $e->getMessage(), 14);
            
        }catch(Exception $e){
            throw new Exception("Ocorreu um erro inesperado " . $e->getMessage() . $e->getCode());
        }
    }

    public static function contarVinculosPorProjeto($id_projeto_fk) {
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("SELECT COUNT(*) FROM vinculo WHERE id_projeto_fk = :id_projeto_fk");
            $sql->bindParam(':id_projeto_fk', $id_projeto_fk, PDO::PARAM_INT);
            $sql->execute();
            return $sql->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("Erro ao contar vínculos: " . $e->getMessage());
        }
    }

    public static function listar() {
        try {
            $pdo = Conn::getConn();
            $userId = $_SESSION['user_id'];

            $sql = $pdo->prepare("
                SELECT p1.*
                FROM vinculo p1
                INNER JOIN (
                    SELECT id_projeto_fk, MAX(id) AS max_id
                    FROM vinculo
                    WHERE id_user_fk = ?
                    GROUP BY id_projeto_fk
                ) p2 ON p1.id_projeto_fk = p2.id_projeto_fk AND p1.id = p2.max_id
                WHERE p1.id_user_fk = ?
            ");
            $sql->execute([$userId, $userId]);
    
            return $sql->fetchAll(PDO::FETCH_CLASS, Vinculo::class);
        } catch (Exception $e) {
            throw new Exception("Ocorreu um erro inesperado " . $e->getMessage() . $e->getCode());
        }
    }

    public static function listarPorUsuario($userId) {
        try {
            $pdo = Conn::getConn();

            $sql = $pdo->prepare("
            SELECT 
            p1.id_projeto_fk,
            p1.id_user_fk,
            MAX(p1.id) AS id,
            (SELECT p2.status 
            FROM vinculo p2 
            WHERE p2.id_user_fk = p1.id_user_fk 
            AND p2.id_projeto_fk = p1.id_projeto_fk 
            ORDER BY p2.id DESC 
            LIMIT 1) AS status,
            (SELECT p2.descricao 
            FROM vinculo p2 
            WHERE p2.id_user_fk = p1.id_user_fk 
            AND p2.id_projeto_fk = p1.id_projeto_fk 
            ORDER BY p2.id DESC 
            LIMIT 1) AS descricao,
            (SELECT p2.joined 
            FROM vinculo p2 
            WHERE p2.id_user_fk = p1.id_user_fk 
            AND p2.id_projeto_fk = p1.id_projeto_fk 
            ORDER BY p2.id DESC 
            LIMIT 1) AS joined,
            MAX(p1.checkin) AS checkin,
            MAX(p1.checkout) AS checkout,
            SUM(TIMESTAMPDIFF(SECOND, p1.checkin, p1.checkout)) AS totalTime
        FROM vinculo p1
        WHERE p1.id_user_fk = ?
        GROUP BY p1.id_projeto_fk, p1.id_user_fk
    ");

            $sql->execute([$userId]);

            return $sql->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            throw new Exception("Ocorreu um erro inesperado: " . $e->getMessage() . " " . $e->getCode());
        }
    }

    public static function listarTudo() {
        try {
            $pdo = Conn::getConn();
            $userId = $_SESSION['user_id'];

            $sql = $pdo->prepare("SELECT 
                                p1.*,
                                TIMESTAMPDIFF(SECOND, p1.checkin, p1.checkout) AS totalTime
                              FROM 
                                vinculo p1
                              ORDER BY 
                                p1.id DESC
                                LIMIT 30");
            $sql->execute();
    
            return $sql->fetchAll(PDO::FETCH_CLASS, Vinculo::class);
        } catch (Exception $e) {
            throw new Exception("Ocorreu um erro inesperado " . $e->getMessage() . $e->getCode());
        }
    }

    public static function buscar($id) {
        try {
            $conn = Conn::getConn();
            $sql = 'SELECT * FROM vinculo WHERE id = :id';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
        
            $partic = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($partic) {
                $vinculo = new Vinculo();
                $vinculo->iniciar(
                    id: $partic['id'],
                    id_user_fk: $partic['id_user_fk'],
                    id_projeto_fk: $partic['id_projeto_fk'],
                    checkin: $partic['checkin'],
                    checkout: $partic['checkout'],
                    status: $partic['status'],
                    descricao: $partic['descricao'],
                    joined: $partic['joined'],
                );
                return $vinculo;
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar relacionamento: " . $e->getMessage(), 14);
        }
    }   

    public static function isJoined($projectId, $userId) {
        try {
            $pdo = Conn::getConn();
    
            $sql = $pdo->prepare("
                SELECT joined
                FROM vinculo
                WHERE id_projeto_fk = ? AND id_user_fk = ?
                ORDER BY id DESC
                LIMIT 1
            ");
            $sql->execute([$projectId, $userId]);
    
            $result = $sql->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result['joined'] == 1;
            }
            return false; // Se não houver entrada para o projeto e usuário
        } catch (Exception $e) {
            throw new Exception("Ocorreu um erro inesperado " . $e->getMessage() . $e->getCode());
        }
    }

    public static function alterar(Vinculo $vinculo){
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("UPDATE vinculo SET descricao = ?, joined = ? WHERE id = ?");
            $sql->execute([
                $vinculo->__get("descricao"),
                $vinculo->__get('joined'),
                $vinculo->__get('id')
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao salvar no banco de dados: " . $e->getMessage(), 14);
        } catch (Exception $e) {
            throw new Exception("Ocorreu um erro inesperado: " . $e->getMessage() . $e->getCode());
        }
    }

    public static function excluir($id){
        try{
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("DELETE FROM vinculo WHERE id =?");
            $sql-> execute([$id]);
        }catch(Exception $e){
            throw new Exception("Ocorreu um erro inesperado ". $e->getMessage(). $e->getCode());
        }
    }


}