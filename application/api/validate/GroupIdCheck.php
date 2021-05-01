<?php
/**
 * User: ruoning
 * Date: 2021/5/1
 * motto: 知行合一!
 */


namespace app\api\validate;


class GroupIdCheck extends BaseValidate
{
    protected $rule = [
        'group_id' => 'require|isMustInteger',
    ];
    protected $message = [

    ];
}