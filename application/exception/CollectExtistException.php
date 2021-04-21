<?php


namespace app\exception;


class CollectExtistException extends BaseException
{
    public $code = "400";
    public $msg = "用户已经收藏过此文章";
    public $errorCode = "90001";
}