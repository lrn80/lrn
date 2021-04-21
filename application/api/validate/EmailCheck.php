<?php
/**
 * User: ruoning
 * Date: 2021/3/13
 * motto: 知行合一!
 */


namespace app\api\validate;


class EmailCheck extends BaseValidate
{
    protected $rule = [
        'email' => 'require|email',
    ];

    protected $message = [

    ];
}