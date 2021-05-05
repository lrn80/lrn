<?php
/**
 * User: ruoning
 * Date: 2021/4/24
 * motto: 知行合一!
 */


namespace app\api\service;
use app\api\model\Auth as AuthModel;
use app\api\model\AuthGroup;
use app\api\model\UserAuth;
use app\exception\AuthException;
use think\Exception;
use think\Log;

class Auth
{
    public static function getAuthList()
    {
        $authModel = new AuthModel();
        $list = $authModel->getList([], 0);
        foreach ($list as &$info){
            $info['auth_id'] = $info['id'];
            unset($info['id']);
        }

        return $list;
    }

    public static function addAuth($name, $model_name)
    {
        $authModel = new AuthModel();
        $data = [
            'name' => $name,
            'controller_name' => $model_name
        ];
        $info = $authModel->getOne($data);
        if ($info){
            throw new AuthException([
                'msg' => '该模块已存在，请勿重复添加'
            ]);
        }
        $res = $authModel->insert($data);
        if (!$res) {
            Log::error(__METHOD__ . ' 增加权限失败 name: ' . $name);
            return false;
        }

        return true;
    }

    public static function update($id, $params)
    {
        $authModel = new AuthModel();
        $info = $authModel->find($id);
        if (!$info){
            throw new AuthException([
                'msg' => '你要修改的权限不存在或者已删除'
            ]);
        }

        $data = [
            'id' => $id,
        ];
        if (isset($params['model_name'])){
            $data['controller_name'] = $params['model_name'];
        }

        if (isset($params['name'])){
            $data['name'] = $params['name'];
        }

        $res = $authModel->updateData($data);
        if (!$res) {
            Log::error(__METHOD__ . ' 修改权限失败 data: ' . json_encode($data));
            return false;
        }

        return true;
    }

    public static function del($authId)
    {
        $authModel = new AuthModel();
        $authUserModel = new AuthGroup();
        $info = $authModel->getOne(['id' => $authId]);
        if (!$info){
            throw new AuthException([
                'msg' => '要删除的权限不存在或者已删除'
            ]);
        }

        $authModel->startTrans();
        try {
            $res = $info->delete();
            if (!$res){
                $authModel->rollback();
                throw new Exception();
            }

            $authUserModel->where(['auth_id' => $authId])->delete();
            $authModel->commit();
        } catch (\Exception $e){
            $authModel->rollback();
            throw new AuthException(['msg' => '权限删除失败']);
        }

        return true;
    }
}