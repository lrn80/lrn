<?php
/**
 * User: xiaomin
 * Date: 2019/11/9
 * Time: 21:51
 */

namespace app\api\validate;


class Arti extends BaseValidate
{
    protected $rule =[
        'content' => 'require|max:100',
        'page'    => 'require|isMustInteger'
    ];

    protected $message=[
        'content'=>"输入的建议不能为空或超过最大50个字",
        'page'    => 'page不能为空且为大于0的正整数',
    ];


}