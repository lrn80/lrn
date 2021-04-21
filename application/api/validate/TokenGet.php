<?php
/**
 * User: ruoning
 * Date: 2021/4/15
 * motto: 知行合一!
 */


namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'uid' => 'require|isMustInteger',
    ];

    protected $message = [

    ];
}