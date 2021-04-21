<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/4/14
 * Time: 13:13
 */

namespace app\exception;


use think\Exception;

class BaseException extends Exception
{
    public $code = "400";
    public $msg = "参数错误";
    public $errorCode = "10000";
    public function __construct($params=[])
    {
        if(!is_array($params)){
            throw new Exception("array");
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }
}