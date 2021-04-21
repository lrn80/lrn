<?php


namespace app\api\validate;


use app\exception\BaseException;

class UserAddCategoryException extends BaseException
{
    public $code = "400";
    public $msg = "用户添加频道异常";
    public $errorCode = "60000";
}