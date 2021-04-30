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


        $data['damage_at'] = $params['damage_at'] ?? date('Y-m-d H:i:s');

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

    public static function repair($id)
    {
        $damageModel = new DamageModel();
        $info = $damageModel->find($id);
        if (!$info){
            throw new DamageException([
                'msg' => '破损订单不存在或者已删除'
            ]);
        }

        if ($info->getData('repair') == self::REPAIR_STATUS_SUCCESS){
            throw new DamageException([
                'msg' => '破损订单已修复请勿重复操作'
            ]);
        }

        $info->repair = self::REPAIR_STATUS_SUCCESS;
        $info->repair_at = date('Y-m-d H:i:s');
        $res = $info->save();
        if (!$res){
            throw new DamageException([
                'msg' => '修改破损状态失败，请稍后再试～'
            ]);
        }

        return true;
    }
}