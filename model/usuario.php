<?php
namespace App\model;

class Usuario{
    private $id, $groupID, $nome, $sobrenome, $data_nascimento, $cpf, $ddd, $telefone, $username, $pass;

    public function __construct(){}

    public function iniciar($id = "", $groupID = "", $nome = "", $sobrenome = "", $data_nascimento = "", $cpf = "", $ddd = "", $telefone = "", $username = "", $pass = ""){
        $this->id = $id;
        $this->groupID = $groupID;
        $this->nome = $nome;
        $this->sobrenome = $sobrenome;
        $this->data_nascimento = $data_nascimento;
        $this->cpf = $cpf;
        $this->ddd = $ddd;
        $this->telefone = $telefone;
        $this->username = $username;
        $this->pass = $pass;
    }

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        if ($atributo == 'pass') {
            $this->setpass($valor);
        } else {
            $this->$atributo = $valor;
        }
    }

    private function setpass($pass) {
        $this->pass = password_hash($pass, PASSWORD_ARGON2I);
    }

}