<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/11/8
 * Time: 14:33
 */

namespace app\exception;


class LoginException extends BaseException {
    public $code = "400";
    public $msg = "用户不存在或者密码有误";
    public $errorCode = "10000";

}