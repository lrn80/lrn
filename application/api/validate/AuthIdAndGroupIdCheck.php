<?php
/**
 * User: ruoning
 * Date: 2021/5/5
 * motto: 知行合一!
 */


namespace app\api\validate;


class AuthIdAndGroupIdCheck extends BaseValidate
{
    protected $rule = [
        //'news_id' => 'require|isMustInteger',
        'auth_ids' => 'require|max:40|min:1',
        'group_id' => 'require|isMustInteger',
    ];

    protected $message = [
    ];
}