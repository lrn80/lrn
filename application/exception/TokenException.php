<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/4/15
 * Time: 17:06
 */

namespace app\exception;


class TokenException extends BaseException
{
    public $code = "401";
    public $msg = "令牌不存在或者令牌已过期";
    public $errorCode = "10001";
}