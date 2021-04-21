<?php


namespace app\api\validate;


class ArticleLimitCheck extends  BaseValidate
{
    protected $rule = [

        'limit'=>'require|isMustInteger'
    ];
    protected $message = [

        'limit'=>'limit不是正整数'
    ];
}