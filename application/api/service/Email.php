<?php
/**
 * User: ruoning
 * Date: 2021/3/14
 * motto: 知行合一!
 */


namespace app\api\service;

use app\api\model\Redis;
use think\Log;
use app\api\model\User as UserModel;

class Email
{
    const  REDIS_TIMEOUT = 300; // redis 过期时间300秒

    public function __construct()
    {
    }

    public static function sendCode($params)
    {
        $redis = Redis::getRedis();
        $email = $params['email'];
        if ($redis->exists(config('setting.redis_login_code_prefix') . $email)) {
            return $redis->get(config('setting.redis_login_code_prefix') . $email);
        }

        $code = self::_getCode($email);
        $title = "亲爱的用户：{$email}";
        $message = "<b>您的验证码是：$code</b>，有效期为5分钟哦。如果非本人操作无需理会！";

        $succ = \Mail::sendEmail($email, $email, $title, $message);
        if (!$succ){
            return false;
        }

        return $code;
    }

    private static function _getCode($email)
    {
        $code = random_int(1000, 9999);
        $succ = Redis::getRedis()->set(config('setting.redis_login_code_prefix') . $email, $code, self::REDIS_TIMEOUT);
        if (!$succ) {
            Log::error(__METHOD__ . 'redis cache code fail email' . $code);
        }

        return $code;
    }

    public static function verifyCode($params)
    {
        $redis = Redis::getRedis();
        if (!isset($params['code'])) {
            return false;
        }

        $code = $params['code'];
        $email = $params['email'];
        if ($code == $redis->get(config('setting.redis_login_code_prefix') . $email)) {
            return true;
        }

        return false;
    }
}