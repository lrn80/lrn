<?php


namespace app\api\validate;


class BooksIdCheck extends BaseValidate
{
    protected $rule = [
        'id'=>'require|isMustInteger',
        //'discuss_id'=>'require|isMustInteger'
    ];
    protected $message = [
        //'discuss_id'=>'discuss_id不合法',
        'id'=>'news_id不合法'
    ];
}