<?php


namespace app\api\validate;


class PageParamCheck extends BaseValidate
{
    protected $rule = [
        'page'=>'isMustInteger'
    ];
    protected $message = [

        'page'=>'page不是正整数'
    ];
}