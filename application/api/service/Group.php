<?php


namespace app\api\service;
use app\api\model\Auth as AuthModel;
use app\api\model\AuthGroup;
use \app\api\model\Group as GroupModel;
use app\exception\AuthException;
use think\Db;

class Group
{
    public static function getGroupList()
    {
        $groupModel = new GroupModel();
        return $groupModel->getList([], 0);
    }

    public static function authIdToGroupId($authIds, $groupId)
    {
        $authModel = new AuthModel();
        $groupModel = new GroupModel();
        $authGroupModel = new AuthGroup();
        $authIds = explode(',', $authIds);
        $authGroupModel->startTrans();
        foreach ($authIds as $authId) {
            $authInfo = $authModel->getOne(['id' => $authId]);
            if (!$authInfo) {
                throw new AuthException([
                    'msg' => '该权限模块不存在或者已删除~'
                ]);
            }

            $groupInfo = $groupModel->getOne(['id' => $groupId]);
            if (!$groupInfo) {
                throw new AuthException([
                    'msg' => '该分组模块不存在，或者已删除'
                ]);
            }

            $insertData = [
                'group_id' => $groupId,
                'auth_id' => $authId,
            ];

            try {
                $authGroupModel->insert($insertData);
            } catch (\Exception $e) {
                $authGroupModel->rollback();
                throw new AuthException([
                    'msg' => '重复添加权限模块auth_id: ' . $authId
                ]);
            }
        }

        $authGroupModel->commit();
        return true;
    }

    public static function groupAuthList()
    {
        $list = Db::table('auth_group')->alias('ag')->join('group g', 'ag.group_id = g.id')
                ->join('auth a', 'ag.auth_id = a.id')
                ->field('ag.group_id,g.name as group_name,ag.auth_id,a.name,a.controller_name')->select();
        return $list;
    }

    public static function groupInfo($group_id)
    {
        $list = Db::table('auth_group')->alias('ag')->join('auth a', 'ag.auth_id = a.id')
               ->field('ag.auth_id,a.name,a.controller_name')->where(['group_id' => $group_id])->select();
        return $list;
    }
}