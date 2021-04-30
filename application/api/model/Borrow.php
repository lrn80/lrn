<?php


namespace app\api\model;


class Borrow extends BaseModel
{
    const RETURN_DONE = 1;
    const RETURN_UNDONE = 0;
    public $hidden = ['create_time', 'update_time'];
    public function getReturnAtAttr($value, $arr){
        if ($arr['borrow_status'] == self::RETURN_UNDONE) {
            $value = '';
        }

        return $value;
    }

    public function getMarkAttr($value){
        if ($value == '无'){
            return '';
        }
    }
}