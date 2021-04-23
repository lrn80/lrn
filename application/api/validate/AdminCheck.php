<?php


namespace app\api\validate;


class AdminCheck extends BaseValidate
{
    protected $rule = [
        'name' => 'max:50|min:1',
    ];

    protected $message = [
        //'birth' => 'birth格式必须是YYYY-mm-dd格式'
    ];
}