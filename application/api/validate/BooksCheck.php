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
        'call_number' => 'require|max:40|min:1',
        'location' => 'require|max:40|min:1', // 馆藏地
        'press' => 'require|max:40|min:1',  // 出版社
        'status' => 'in:1,0' // 1可借 0不可借
    ];

    protected $message = [

    ];
}