<?php


class PdoObj
{
    const PDODSN= "mysql:host=129.204.63.87;dbname=internation;port=3306;charset = utf8";
    const PDOUSERNAME="LRN";
    const PDOPASSWORD="lrn123";
    private static $_instance = null; //创建一个静态的实例
    private function __construct()
    {

    }
    public static function getPDO(){
        if(!self::$_instance instanceof \PDO){
            self::$_instance = new \PDO(self::PDODSN,self::PDOUSERNAME,self::PDOPASSWORD);
        }

        return self::$_instance;
    }
    private function __clone()
    {
    }
}