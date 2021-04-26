<?php


namespace app\api\validate;


class BNoCheck extends BaseValidate
{
    protected $rule = [
        'b_no'=>'require|max:50|min:1',
        //'discuss_id'=>'require|isMustInteger'
    ];
    protected $message = [
        //'discuss_id'=>'discuss_id不合法',
        'id'=>'news_id不合法'
    ];
}