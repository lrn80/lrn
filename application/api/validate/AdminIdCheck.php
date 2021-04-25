<?php


namespace app\api\validate;


class AdminIdCheck extends BaseValidate
{
    protected $rule = [
        //'news_id' => 'require|isMustInteger',
        'id' => 'require|isMustInteger'
    ];

    protected $message = [
    ];
}