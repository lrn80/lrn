<?php


namespace app\api\validate;


class FollowIdCheck extends BaseValidate
{
    protected $rule = [
        'follow_id' => 'require|isMustInteger',
    ];

    protected $message = [
        'follow_id' => 'follow_id不是正整数',
    ];
}