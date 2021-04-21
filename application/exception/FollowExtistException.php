<?php


namespace app\exception;


class FollowExtistException extends BaseException
{
    public $code = "400";
    public $msg = "你已经关注了此用户不要重复关注了";
    public $errorCode = "100000";
}