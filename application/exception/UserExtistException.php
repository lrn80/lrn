<?php


namespace app\exception;


class UserExtistException extends BaseException
{
    public $code = "400";
    public $msg = "用户已存在，请前往登陆";
    public $errorCode = "20002";
}