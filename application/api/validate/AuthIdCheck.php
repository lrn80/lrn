<?php


namespace app\api\validate;


class AuthIdCheck extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isMustInteger',
    ];

    protected $message = [

    ];
}