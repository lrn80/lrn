<?php

namespace app\api\validate;


class IDCheck extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isMustInteger',
    ];
    protected $message = [
        'id' => 'id不是正整数',
    ];
}