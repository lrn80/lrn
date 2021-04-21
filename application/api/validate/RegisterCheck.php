<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/11/8
 * Time: 14:02
 */

namespace app\api\validate;


class RegisterCheck extends BaseValidate {
    protected $rule = [
        'email' => 'require|email',
        'password' => 'require|max:12|min:5',
        're_password' => 'require|max:12|min:5',
        'code' =>  'require|max:5|min:1',
    ];
    protected $message = [

    ];
}