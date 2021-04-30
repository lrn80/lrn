<?php
/**
 * User: ruoning
 * Date: 2021/4/30
 * motto: 知行合一!
 */


namespace app\api\validate;


class StatusCheck extends BaseValidate
{
    protected $rule = [
        'status' => 'require|in:0,1,2',
    ];

    protected $message = [

    ];
}