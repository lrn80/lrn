<?php


namespace app\exception;


class UserCollectException extends BaseException
{
    public $code = "400";
    public $msg = "用户收藏的文章不存在或者已删除";
    public $errorCode = "90000";
}