<?php


namespace app\api\validate;


class ArticleCateIdIntCheck extends  BaseValidate
{
    protected $rule = [
        'cateid' => 'isMustInteger',
        'page'=> 'isMustInteger'

    ];

    protected $message = [
        'cateid'  => 'cateid参数必须为正整数',
        'page' => 'page参数必须是正整数'
    ];
}