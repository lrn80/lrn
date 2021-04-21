<?php


namespace app\exception;


class FollowException extends BaseException
{
    public $code = "400";
    public $msg = "关注用户失败，请稍后再试～";
    public $errorCode = "100001";
}