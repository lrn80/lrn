<?php


namespace app\api\validate;


class ArticleCateCheck extends BaseValidate
{
    protected $rule = [
        'page' => 'isMustInteger',
        'limit' => 'isMustInteger'
    ];

    protected $message = [
        'page' => '分类参数必须是正整数',
        'limit' => '索要文章参数必须是正整数'
    ];
}