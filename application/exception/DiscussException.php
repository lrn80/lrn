<?php


namespace app\exception;


class DiscussException extends BaseException
{
    public $code = "400";
    public $msg = "评论不存在或者已删除";
    public $errorCode = "70000";
}