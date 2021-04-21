<?php


namespace app\api\validate;


class UserCategoryCheck extends BaseValidate
{
    protected $rule = [
        //'news_id' => 'require|isMustInteger',
        'cids' => 'require|max:200|min:1'
    ];

    protected $message = [
        'key' => 'cids不合法'
    ];
}