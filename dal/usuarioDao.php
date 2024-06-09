<?php
namespace App\dal;
use App\model\Usuario;
use \Exception;
use \PDOException;
use \PDO;

abstract class UsuarioDao{

    public static function cadastrar(Usuario $usuario){
        try{
            $pdo = Conn::getConn();// id, group, nome, sobrenome, data_nasc, cpf, ddd, telefone, user, pass
            $sql = $pdo->prepare("INSERT INTO usuarios VALUES (null, 1, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sql-> execute([$usuario->__get("nome"), 
                            $usuario->__get("sobrenome"),
                            $usuario->__get("data_nascimento"),
                            $usuario->__get("cpf"),
                            $usuario->__get("ddd"),
                            $usuario->__get("telefone"),
                            $usuario->__get("username"),
                            $usuario->__get("pass"),
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
            $sql = $pdo->prepare("SELECT * FROM usuarios");
            $sql-> execute();

            return $sql->fetchAll(PDO::FETCH_CLASS, Usuario::class);
        }catch(Exception $e){
            throw new Exception("Ocorreu um erro inesperado " . $e->getMessage() . $e->getCode());
        }
    }

    public static function validarUnicidadeCPF($cpf) {
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE cpf = :cpf");
            $sql->bindParam(':cpf', $cpf, PDO::PARAM_STR);
            $sql->execute();
            $count = $sql->fetchColumn();

            if ($count > 0) {
                throw new Exception("O CPF já está em uso.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function validarUnicidadeUsername($username) {
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE username = :username");
            $sql->bindParam(':username', $username, PDO::PARAM_STR);
            $sql->execute();
            $count = $sql->fetchColumn();

            if ($count > 0) {
                throw new Exception("O username já está em uso.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function buscar($id) {
        try {
            $conn = Conn::getConn();
            $sql = 'SELECT * FROM usuarios WHERE id = :id';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
        
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($user) {
                $usuario = new Usuario();
                $usuario->iniciar(
                    id: $user['id'],
                    groupID: $user['groupID'],
                    nome: $user['nome'],
                    sobrenome: $user['sobrenome'],
                    data_nascimento: $user['data_nascimento'],
                    cpf: $user['cpf'],
                    ddd: $user['ddd'],
                    telefone: $user['telefone'],
                    username: $user['username'],
                    pass: $user['pass'],
                );
                return $usuario;
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar usuário: " . $e->getMessage(), 14);
        }
    }   

    public static function buscarPorUsername($username) {
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
            $sql->execute([$username]);
            $user = $sql->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                $usuario = new Usuario();
                $usuario->iniciar(
                    id: $user['id'],
                    groupID: $user['groupID'],
                    nome: $user['nome'],
                    sobrenome: $user['sobrenome'],
                    data_nascimento: $user['data_nascimento'],
                    cpf: $user['cpf'],
                    ddd: $user['ddd'],
                    telefone: $user['telefone'],
                    username: $user['username'],
                    pass: $user['pass']
                );
                return $usuario;
            }
            
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar usuário: " . $e->getMessage(), 14);
        }
    }

    public static function buscarPorCPF($cpf) {
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("SELECT * FROM usuarios WHERE cpf = ?");
            $sql->execute([$cpf]);
            $user = $sql->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                $usuario = new Usuario();
                $usuario->iniciar(
                    id: $user['id'],
                    groupID: $user['groupID'],
                    nome: $user['nome'],
                    sobrenome: $user['sobrenome'],
                    data_nascimento: $user['data_nascimento'],
                    cpf: $user['cpf'],
                    ddd: $user['ddd'],
                    telefone: $user['telefone'],
                    username: $user['username'],
                    pass: $user['pass']
                );
                return $usuario;
            }
            
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar usuário: " . $e->getMessage(), 14);
        }
    }

    public static function alterar(Usuario $usuario){
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("UPDATE usuarios SET groupID = ?, nome = ?, sobrenome = ?, ddd = ?, telefone = ?, username = ? WHERE id = ?");
            $sql->execute([
                $usuario->__get("groupID"), 
                $usuario->__get("nome"), 
                $usuario->__get("sobrenome"),
                $usuario->__get("ddd"),
                $usuario->__get("telefone"),
                $usuario->__get("username"),
                $usuario->__get("id")
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao salvar no banco de dados: " . $e->getMessage(), 14);
        } catch (Exception $e) {
            throw new Exception("Ocorreu um erro inesperado: " . $e->getMessage() . $e->getCode());
        }
    }

    public static function atualizarSenha(Usuario $usuario){
        try {
            $pdo = Conn::getConn();
            $sql = $pdo->prepare("UPDATE usuarios SET pass = ? WHERE id = ?");
            $sql->execute([
                $usuario->__get("pass"), 
                $usuario->__get("id")
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
            $sql = $pdo->prepare("DELETE FROM usuarios WHERE id =?");
            $sql-> execute([$id]);
        }catch(Exception $e){
            throw new Exception("Ocorreu um erro inesperado ". $e->getMessage(). $e->getCode());
        }
    }

}