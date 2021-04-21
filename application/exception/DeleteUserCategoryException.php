<?php


namespace app\exception;


class DeleteUserCategoryException extends BaseException
{
    public $code = "400";
    public $msg = "频道数据不存在或者已删除";
    public $errorCode = "70001";
}