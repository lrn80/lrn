<?php


namespace app\api\validate;


class CategoryIdCheck extends BaseValidate
{
    protected $rule = [
        //'news_id' => 'require|isMustInteger',
        'cid' => 'require|isMustInteger'
    ];

    protected $message = [
        'key' => 'cid不合法'
    ];
}