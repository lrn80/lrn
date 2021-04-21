<?php


namespace app\api\validate;


class UidNewsIDContentCheck extends BaseValidate
{
    protected $rule = [
        'news_id' => 'require|isMustInteger',
        //'uid' => 'require|isMustInteger',
        'content' => 'require|max:150|min:1'
    ];

    protected $message = [
        //'key' => 'cids不合法'
    ];
}