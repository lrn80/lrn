<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/4/9
 * Time: 19:46
 */

namespace app\api\service;


use app\api\model\Redis;
use app\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken(){
        $randChar = getRandChar(32);
        //获取当前时间戳
        $timestamp = $_SERVER['REQUEST_TIME'];
        $tokenSalt = config('secure.token_salt');
        return md5($randChar.$timestamp.$tokenSalt);
    }

    public static function getCurrentTokenVar($key = null){
        $redis = Redis::getRedis();
        $token = Request::instance()->header("token");
        $value = $redis->get($token);
        if(!$value){
            throw new TokenException();
        }else{
            if ($key){
                if(!is_array($value)){
                    $value = json_decode($value,true);
                }

                if(array_key_exists($key,$value)){
                    return $value[$key];
                }else{
                    throw new Exception('尝试获取的Token变量不存在');
                }
            }else{
                $value = json_decode($value,true);
                unset($value['id']);
                $value = json_encode($value);
                return $value;
            }
        }
    }

    public static function getIdByTokenVar(){
        $uid = self::getTokenVarByToken('id');
        return $uid;
    }

    public static function getSessionKeyByTokenVar(){
        $uid = self::getTokenVarByToken('session_key');
        return $uid;
    }

    public static function getUserNameByTokenVar(){
        $uid = self::getTokenVarByToken('username');
        return $uid;
    }

    public static function getImgUrlTokenVar(){
        $uid = self::getTokenVarByToken('avatar');
        return $uid;
    }

    public static function checkToken(){
        $token = Request::instance()->header("token");
        $value = Cache::get($token);
        if (!$value) {
            throw new TokenException();
        }
    }

    public static function getInfoByTokenVar(){
        $info = self::getTokenVarByToken();
        return $info;
    }

}