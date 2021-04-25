<?php


namespace app\api\validate;


class BooksUpdateCheck extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isMustInteger',
        'bname' => 'max:40|min:1',
        'author' => 'max:40|min:1',
        'price' => 'float', // 价格（分）
        'total_stock' => 'isMustInteger',
        'in_library_time' => 'dateFormat:Y-m-d H:i:s',
        'now_stock' => 'isMustInteger',
    ];

    protected $message = [

    ];
}