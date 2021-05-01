<?php


namespace app\api\model;


class Damage extends BaseModel
{
    const REPAIR_STATUS_SUCCESS = 1; // 已修复
    const REPAIR_STATUS_NORMAL = 0; // 未修复
    const REPAIR_STATUS_ALL = 2; //全部
    public function getRepairAtAttr($value, $data){
        if ($data['repair'] == self::REPAIR_STATUS_NORMAL){
            $value = '';
        }

        return $value;
    }
}