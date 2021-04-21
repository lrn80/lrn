<?php


class Redisobj
{
    const REDISHOST="129.211.60.146";
    const RERDISPORT="5525";
    private static $_instance = null; //创建一个静态的实例

    private function __construct()
    {

    }
    public static function getRedis(){
        if(!self::$_instance instanceof \Redis){
            self::$_instance = new \Redis();
            self::$_instance->connect(self::REDISHOST,self::RERDISPORT);
        }

        return self::$_instance;
    }
    private function __clone()
    {

    }

}