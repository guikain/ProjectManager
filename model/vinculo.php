<?php
namespace App\model;

class Vinculo{
    private $id, $id_user_fk, $id_projeto_fk, $checkin, $checkout, $status, $descricao, $joined, $totalTime;

    public function __construct(){}

    public function iniciar( $id= '', $id_user_fk = '', $id_projeto_fk = '', $checkin = '', $checkout= '', $status= '', $descricao = '', $joined = 0, $totalTime = 0){
        $this->id = $id;
        $this->id_user_fk = $id_user_fk;
        $this->id_projeto_fk = $id_projeto_fk;
        $this->checkin = $checkin;
        $this->checkout = $checkout;
        $this->status = $status;
        $this->descricao = $descricao;
        $this->joined = $joined;
        $this->totalTime = $totalTime;
    }

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }
    

}