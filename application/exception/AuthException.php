<?php
/**
 * User: ruoning
 * Date: 2021/4/24
 * motto: 知行合一!
 */


namespace app\exception;


class AuthException extends BaseException
{
    public $code = "400";
    public $msg = "权限操作异常，请稍后再试~";
    public $errorCode = "100000";
}