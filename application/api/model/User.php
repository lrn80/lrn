<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/11/8
 * Time: 14:20
 */

namespace app\api\model;


use app\exception\RegisterException;
use think\exception\PDOException;

class User extends BaseModel {

    protected $hidden = ['update_time', 'password'];
    const SEX_MAN = 1;
    const SEX_WOMAN = 2;
    public function getAvatarAttr($value) {
        return $this->prefixImgUrl($value);
    }

    public function getSexAttr($value) {
        if ($value == self::SEX_MAN) {
            return "男";
        } else {
            return "女";
        }
    }
    /**
     * 检查用户名密码是否正确
     */
    public function getUserInfo($data){
        return self::where('email', '=', $data['email'])
                    ->where('password', '=', md5($data['password']))
                    ->find();
    }

    /**
     * 插入新用户
     */
    public function saveUser($data){
        return self::save([
            'username' => $data['email'],
            'email' => $data['email'],
            'password' => md5($data['password']),
            'avatar' => '/upload/user/924e655022aee453710743990c24134c.jpg',
        ]);
    }

    public function getUserByCondition($condition, $field = [])
    {
        return self::where($condition)->field($field)->find();
    }
}
