<?php


namespace app\exception;


class DamageException extends BaseException
{
    public $code = "400";
    public $msg = "破损订单异常，请稍后再试～";
    public $errorCode = "500001";
}