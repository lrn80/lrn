<?php


namespace app\exception;


class AdminException extends BaseException
{
    public $code = "400";
    public $msg = "用户信息操作异常，请稍后再试～";
    public $errorCode = "20000";
}