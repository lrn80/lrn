<?php
/**
 * User: ruoning
 * Date: 2021/4/24
 * motto: 知行合一!
 */


namespace app\api\validate;


class AuthCheck extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isMustInteger',
        'model_name' => 'max:30|min:1',
        'name' => 'max:30|min:1',
    ];

    protected $message = [

    ];
}