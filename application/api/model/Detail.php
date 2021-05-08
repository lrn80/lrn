<?php


namespace app\api\model;


class Detail extends BaseModel
{
    const CAN_LEAD = 1;
    const CAN_NOT_LEAD = 0;
//    public function getStatusAttr($value){
//        if ($value == self::CAN_LEAD) {
//            $value = '可借阅';
//        } else {
//            $value = '不可借阅';
//        }
//
//        return $value;
//    }
}