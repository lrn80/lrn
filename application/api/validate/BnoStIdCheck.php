<?php


namespace app\api\validate;


class BnoStIdCheck extends BaseValidate
{
    protected $rule = [
        'b_no'=>'require|max:50|min:1',
        'st_id' => 'require|max:50|min:1'
        //'discuss_id'=>'require|isMustInteger'
    ];
    protected $message = [

    ];
}