<?php
/**
 * User: ruoning
 * Date: 2021/4/24
 * motto: 知行合一!
 */


namespace app\api\validate;


class AuthNameCheck extends BaseValidate
{
    protected $rule = [
        //'news_id' => 'require|isMustInteger',
        'name' => 'require|max:30|min:1',

    ];

    protected $message = [

    ];
}