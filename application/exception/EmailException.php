<?php
/**
 * User: ruoning
 * Date: 2021/3/14
 * motto: 知行合一!
 */


namespace app\exception;


class EmailException extends BaseException
{
    public $code = "400";
    public $msg = "邮件发送失败请稍后再试";
    public $errorCode = "50000";
}