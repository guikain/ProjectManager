<?php
namespace App\dal;
use \PDO;
use \PDOException;

abstract class Conn{
    private static $conn;
    private static $host = "localhost:3306";
    private static $dbname = "projmgr";
    private static $login = "root";
    private static $senha = "";

    public static function getConn(){
        try{
            if (!isset(self::$conn)){
                self::$conn = new PDO("mysql:host=" . self::$host . "; dbname=" . self::$dbname, self::$login, self::$senha);
            }
            return self::$conn;
        }catch(PDOException $e){
            die("Erro ao conectar ao banco: " . $e->getMessage());
        }
    }


}