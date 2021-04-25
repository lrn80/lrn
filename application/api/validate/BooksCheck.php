<?php


namespace app\api\validate;


class BooksCheck extends BaseValidate
{
    protected $rule = [
        'bname' => 'require|max:40|min:1',
        'author' => 'require|max:40|min:1',
        'price' => 'require|float', // 价格（分）
        'total_stock' => 'require|isMustInteger',
        'in_library_time' => 'require|dateFormat:Y-m-d H:i:s',
        'b_no' => 'require|max:40|min:1',
    ];
    protected $message = [

    ];
}