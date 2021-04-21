<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/4/15
 * Time: 16:49
 */

namespace app\api\service;

use app\api\model\Redis;
use app\exception\TokenException;
use app\exception\UserNotExtistException;
use app\exception\WeChatException;
use think\Exception;
use app\api\model\User;

class TokenUser extends Token {
    protected $user = [];

    function __construct() {
    }

    /**
     * 获取令牌，并存入缓存
     * @param $uid
     * @return string
     * @throws Exception
     * @throws TokenException
     * @throws UserNotExtistException
     */
    public function get($uid) {
        $this->user = (new User())->getUserByCondition(['id' => $uid]);
        if (!$this->user) {
            throw new UserNotExtistException();
        }

        $cachedValue = $this->prepareCachedValue($this->user);
        //写入缓存,返回令牌
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    /**
     * 写入缓存，并返回Token
     * @param $cacheValue
     * @return string
     * @throws Exception
     * @throws TokenException
     */
    private function saveToCache($cacheValue) {
        //生成随机写到Token基类中，供其他service类应用
        $redis = Redis::getRedis();
        $key        = self::generateToken();
        $cacheValue = json_encode($cacheValue);
        $expire_in  = config('secure.token_expire_in');
        $result     = $redis->set($key, $cacheValue, $expire_in);

        if (!$result) {
            throw new TokenException([
                'msg'       => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }

        return $key;
    }

    /**
     * 准备缓存数据
     * @param $wxResult
     * @param $uid
     * @return mixed
     */
    private function prepareCachedValue($user) {
        $cachedValue  = $user;
        $cachedValue['scope'] = 16;
        return $cachedValue;
    }
}