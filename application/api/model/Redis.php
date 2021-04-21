<?php


namespace app\api\model;


class Redis
{
    private static $_instance = null; //创建一个静态的实例
    private function __construct()
    {

    }
    public static function getRedis(){
        if(!self::$_instance instanceof \Redis){
            self::$_instance = new \Redis();
            self::$_instance->connect(config("setting.redis_host"),config("setting.redis_port"));
            self::$_instance->auth(config('setting.redis_password'));
        }

        return self::$_instance;
    }
    private function __clone()
    {

    }

}