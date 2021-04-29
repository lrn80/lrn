<?php


namespace app\api\validate;


class DamageCheck extends BaseValidate
{
    protected $rule = [
        'b_no' => 'require|max:40|min:1',
        'bname' => 'require|max:40|min:1',
        'damage_at' => 'require|dateFormat:Y-m-d H:i:s',
    ];

    protected $message = [

    ];
}