<?php


namespace app\api\model;


class Books extends BaseModel
{
    public $hidden = ['update_time'];
    public function getPriceAttr($value) {
        $value = $value / 100;
        return $value;
    }
}