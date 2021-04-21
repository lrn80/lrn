<?php
/**
 * User: ruoning
 * Date: 2021/4/19
 * motto: 知行合一!
 */


namespace app\exception;


class DelHistoryException extends BaseException
{
    public $code = "400";
    public $msg = "用户历史删除异常";
    public $errorCode = "70000";
}