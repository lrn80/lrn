<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/4/15
 * Time: 17:06
 */

namespace app\exception;


class SucceedMessage extends BaseException
{
    public $code = "200";
    public $msg = "成功";
    public $errorCode = "00000";
}