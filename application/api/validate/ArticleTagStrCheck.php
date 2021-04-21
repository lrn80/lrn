<?php


namespace app\api\validate;


class ArticleTagStrCheck extends BaseValidate
{
    protected $rule = [
        'tag' => 'listVarString',
        'page'=> 'isMustInteger'

    ];

    protected $message = [
        'tag'  => 'tag参数必须为后端相对应的参数,请不要随便传或者恶意传值!!!',
        'page' => 'page参数必须是正整数'
    ];
}