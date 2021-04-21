<?php


namespace app\api\validate;


class NewsIdCheck extends BaseValidate
{
    protected $rule = [
        'news_id'=>'require|isMustInteger',
        //'discuss_id'=>'require|isMustInteger'
    ];
    protected $message = [
        //'discuss_id'=>'discuss_id不合法',
        'news_id'=>'news_id不合法'
    ];
}