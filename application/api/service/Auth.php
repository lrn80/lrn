<?php
/**
 * User: ruoning
 * Date: 2021/4/24
 * motto: 知行合一!
 */


namespace app\api\service;
use app\api\model\Auth as AuthModel;
use app\exception\AuthException;
use think\Log;

class Auth
{
    public static function getAuthList()
    {
        $authModel = new AuthModel();
        return $authModel->getList([], 0);
    }

    public static function addAuth($name)
    {
        $authModel = new AuthModel();
        $data = [
            'name' => $name,
        ];
        $res = $authModel->insert($data);
        if (!$res) {
            Log::error(__METHOD__ . ' 增加权限失败 name: ' . $name);
            return false;
        }

        return true;
    }

    public static function update($id, $name)
    {
        $authModel = new AuthModel();
        $info = $authModel->find($id);
        if (!$info){
            throw new AuthException([
                'msg' => '权限操作异常，请稍后再试~'
            ]);
        }

        $data = [
            'id' => $id,
            'name' => $name,
        ];
        $res = $authModel->updateData($data);
        if (!$res) {
            Log::error(__METHOD__ . ' 增加权限失败 name: ' . $name);
            return false;
        }

        return true;
    }
}