<?php


namespace app\api\validate;


class NewsPageCheck extends  BaseValidate
{
    protected $rule = [
        'page'=>'require|isMustInteger'
    ];
    protected $message = [

        'page'=>'page不是正整数'
    ];
}