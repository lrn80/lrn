<?php
/**
 * User: ruoning
 * Date: 2021/4/15
 * motto: 知行合一!
 */


namespace app\exception;


class UserNotExtistException extends BaseException
{
    public $code = "400";
    public $msg = "用户不存在";
    public $errorCode = "10001";
}