<?php
namespace App\model;

class Participa{
    private $id, $id_user_fk, $id_projeto_fk, $checkin, $checkout, $descricao;

    public function __construct(){}

    public function iniciar( $id= '', $id_user_fk = '', $id_projeto_fk = '', $checkin = '', $checkout= '', $descricao = ''){
        $this->id = $id;
        $this->id_user_fk = $id_user_fk;
        $this->id_projeto_fk = $id_projeto_fk;
        $this->checkin = $checkin;
        $this->checkout = $checkout;
        $this->descricao = $descricao;
    }

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

}