<?php
namespace App\dal;
use App\model\Projeto;
use \Exception;
use \PDOException;
use \PDO;

abstract class ProjetoDao{

    public static function cadastrar(Projeto $projeto){
        try{
            $pdo = Conn::getConn();
                                // id, nome, prioridade, dificuldade, data_inicio, data_fim, status
            $sql = $pdo->prepare("INSERT INTO projetos VALUES (null, ?, ?, ?, ?, ?, ?, ?)");
            $sql-> execute([$projeto->__get("nome"), 
                            $projeto->__get("prioridade"),
                            $projeto->__get("dificuldade"),
                            $projeto->__get("data_inicio"),
                            $projeto->__get("prazo"),
                            $projeto->__get("data_fim"),
                            $projeto->__get("status")
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
            $sql = $pdo->prepare("SELECT * FROM projetos");
            $sql-> execute();

            return $sql->fetchAll(PDO::FETCH_CLASS, Projeto::class);
        }catch(Exception $e){
            throw new Exception("Ocorreu um erro inesperado " . $e->getMessage() . $e->getCode());
        }
    }

    public static function buscar($id) {
        try {
            $conn = Conn::getConn();
            $sql = 'SELECT * FROM projetos WHERE id = :id';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
        
            $proj = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($proj) {
                $projeto = new Projeto();
                $projeto->iniciar(
                    // id, nome, prioridade, dificuldade, data_inicio, data_fim, status
                    id: $proj['id'],
                    nome: $proj['nome'],
                    prioridade: $proj['prioridade'],
                    dificuldade: $proj['dificuldade'],
                    data_inicio: $proj['data_inicio'],
                    prazo: $proj['prazo'],
                    data_fim: $proj['data_fim'],
                    status: $proj['status'],
                );
                return $projeto;
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar projeto: " . $e->getMessage(), 14);
        }
    }   

    public static function buscarPorNome($nome) {
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("SELECT * FROM projetos WHERE nome = ?");
            $sql->execute([$nome]);
            $proj = $sql->fetch(PDO::FETCH_ASSOC);
            
            if ($proj) {
                $projeto = new Projeto();
                $projeto->iniciar(
                    // id, nome, prioridade, dificuldade, data_inicio, prazo, data_fim, status
                    id: $proj['id'],
                    nome: $proj['nome'],
                    prioridade: $proj['prioridade'],
                    dificuldade: $proj['dificuldade'],
                    data_inicio: $proj['data_inicio'],
                    prazo: $proj['prazo'],
                    data_fim: $proj['data_fim'],
                    status: $proj['status'],
                );
                return $projeto;
            }
            
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar projeto: " . $e->getMessage(), 14);
        }
    }

    public static function alterar(Projeto $projeto){
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("UPDATE projetos SET nome = ?, prioridade = ?, dificuldade = ?, prazo = ?, data_fim = ?, status = ? WHERE id = ?");
            $sql->execute([
                $projeto->__get("nome"), 
                $projeto->__get("prioridade"),
                $projeto->__get("dificuldade"),
                $projeto->__get('prazo'),
                $projeto->__get("data_fim"),
                $projeto->__get("status"),
                $projeto->__get("id"),
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
            $sql = $pdo->prepare("DELETE FROM projetos WHERE id =?");
            $sql-> execute([$id]);
        }catch(Exception $e){
            throw new Exception("Ocorreu um erro inesperado ". $e->getMessage(). $e->getCode());
        }
    }

}