<?php


namespace app\exception;


class StudentException extends BaseException
{
    public $code = "400";
    public $msg = "学生管理操作异常请稍后再试";
    public $errorCode = "70000";
}