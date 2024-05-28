<?php
namespace App\dal;
use App\model\Participa;
use \Exception;
use \PDOException;
use \PDO;

abstract class ParticipaDao{

    public static function cadastrar(Participa $participa){
        try{
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("INSERT INTO participa VALUES (null, ?, ?, ?, ?)");
            $sql-> execute([$participa->__get("id_user_fk"), 
                            $participa->__get("id_projeto_fk"),
                            $participa->__get("checkin"),
                            $participa->__get("checkout"),
                            $participa->__get('descricao')
                        ]);
        }catch(PDOException $e){
            throw new Exception("Erro ao salvar no banco de dados". $e->getMessage(), 14);
            
        }catch(Exception $e){
            throw new Exception("Ocorreu um erro inesperado " . $e->getMessage() . $e->getCode());
        }
    }

    public static function listar(){
        try{
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("SELECT * FROM participa");
            $sql-> execute();

            return $sql->fetchAll(PDO::FETCH_CLASS, Participa::class);
        }catch(Exception $e){
            throw new Exception("Ocorreu um erro inesperado " . $e->getMessage() . $e->getCode());
        }
    }

    public static function buscar($id) {
        try {
            $conn = Conn::getConn();
            $sql = 'SELECT * FROM participa WHERE id_user_fk = :id';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
        
            $partic = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($partic) {
                $participa = new Participa();
                $participa->iniciar(
                    id: $partic['id'],
                    id_user_fk: $partic['id_user_fk'],
                    id_projeto_fk: $partic['id_projeto_fk'],
                    checkin: $partic['checkin'],
                    checkout: $partic['checkout'],
                    descricao: $partic['descricao'],
                );
                return $participa;
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar relacionamento: " . $e->getMessage(), 14);
        }
    }   

    public static function buscarPorNome($nome) {
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("SELECT * FROM projetos WHERE id_user_fk = ? GROUP BY id_user_fk");
            $sql->execute([$nome]);
            $partic = $sql->fetch(\PDO::FETCH_ASSOC);
            if ($partic) {
                $participa = new Participa();
                $participa->iniciar(
                    id: $partic['id'],
                    id_user_fk: $partic['id_user_fk'],
                    id_projeto_fk: $partic['id_projeto_fk'],
                    checkin: $partic['checkin'],
                    checkout: $partic['checkout'],
                    descricao: $partic['descricao'],
                );
                return $participa;
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar relacionamento: " . $e->getMessage(), 14);
        }
    }
}