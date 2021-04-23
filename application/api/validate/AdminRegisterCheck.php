<?php


namespace app\api\validate;


class AdminRegisterCheck extends BaseValidate
{
    protected $rule = [
        'email' => 'require|email',
        'password' => 'require|max:12|min:5',
        're_password' => 'require|max:12|min:5',
        'name' => 'require|max:25',
        'code' =>  'require|max:5|min:1',
    ];

    protected $message = [

    ];
}