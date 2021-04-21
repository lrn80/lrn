<?php


namespace app\api\validate;


class DiscussIdCheck extends BaseValidate
{
    protected $rule = [
        'discuss_id' => 'require|isMustInteger',
        'news_id'=>'require|isMustInteger'
    ];

    protected $message = [
        'discuss_id' => 'discuss_id不合法',
        'news_id'=>'news_id不合法'
    ];
}