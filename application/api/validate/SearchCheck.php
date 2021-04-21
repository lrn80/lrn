<?php


namespace app\api\validate;


class SearchCheck extends BaseValidate
{
    protected $rule = [
        //'news_id' => 'require|isMustInteger',
        'key' => 'require|max:100|min:1',
        'page' => 'require|isMustInteger',
    ];

    protected $message = [
        'key' => '关键字为空或不再查询范围内'
    ];
}