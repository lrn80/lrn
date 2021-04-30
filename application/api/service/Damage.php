<?php


namespace app\api\service;
use \app\api\model\Damage as DamageModel;
use app\exception\DamageException;
use think\Log;

class Damage
{
    const REPAIR_STATUS_SUCCESS = 1; // 已修复
    const REPAIR_STATUS_NORMAL = 0; // 未修复
    const REPAIR_STATUS_ALL = 2; //全部
    public static function getDamageList($status, $page)
    {
        $damageModel = new DamageModel();
        $conditions = [];
        if ($status != self::REPAIR_STATUS_ALL){ // 2全部
            $conditions = [
                'repair' => $status,
            ];
        }
        return $damageModel->getList($conditions, $page);
    }

    public static function add($params)
    {
        $data = [];

        if (isset($params['b_no'])) {
            $data['b_no'] = $params['b_no'];
        }

        if (isset($params['bname'])) {
            $data['bname'] = $params['bname'];
        }

        if (isset($params['damage_at'])){
            $data['damage_at'] = $params['damage_at'];
        }

        $damageModel = new DamageModel();
        $res = $damageModel->insert($data);
        if (!$res){
            Log::error(__METHOD__ . ' 破损订单增加失败 data: ' . json_encode($data));
            throw new DamageException([
                'msg' => '破损订单添加失败请稍后再试～'
            ]);
        }

        return $res;
    }
}