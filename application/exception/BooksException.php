<?php


namespace app\exception;


class BooksException extends BaseException
{
    public $code = "400";
    public $msg = "图书管理操作异常，请稍后再试~";
    public $errorCode = "30000";
}