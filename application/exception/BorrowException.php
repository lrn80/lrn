<?php


namespace app\exception;


class BorrowException extends BaseException
{
    public $code = "400";
    public $msg = "阅览管理操作异常，请稍后再试~";
    public $errorCode = "60000";
}