<?php


namespace app\api\service;

use app\api\model\Admin as AdminModel;
use app\api\model\Auth as AuthModel;
use app\api\model\AuthGroup;
use app\api\model\Group as GroupModel;
use app\exception\AdminException;
use app\exception\AuthException;
use app\exception\LoginException;
use think\Log;

class Admin
{
    /**
     * 查看管理员是否已经注册过了
     * @param $email
     * @return bool
     */
    public static function checkAdminExist($email) {
        $adminModel = new AdminModel();
        $condition = [
            'email' => $email
        ];

        $adminInfo = $adminModel->getOne($condition);
        if ($adminInfo) {
            return true;
        }

        return false;
    }

    public static function login($email, $password)
    {
        $condition = [
            'email' => $email,
            'password' => md5($password)
        ];
        $adminInfo = AdminModel::where($condition)->find();
        if ($adminInfo){
            $token = (new TokenUser())->get($adminInfo['id']);
            $adminInfo['token'] = $token;
        } else {
            throw new LoginException([
                'msg' => '密码错误'
            ]);
        }

        $authGroupList = (new AuthGroup())->getList(['group_id' => $adminInfo['group_id']], 0)->toArray();
        $authIds = array_column($authGroupList, 'auth_id');
        $adminInfo['auth'] = (new AuthModel())->where(['id' => ['in', $authIds]])->field('id,name,controller_name')->select();
        return $adminInfo;
    }

    public static function createAdmin($params)
    {
        $conditions = [
            'email' => $params['email'],
            'password' => md5($params['password']),
            'name' => $params['name'],
            'avatar' => '/upload/user/1.png'
        ];

        $res = (new AdminModel())->insert($conditions);
        if (!$res) {
            Log::error("用户注册管理员失败：condition: " . json_encode($conditions));
            return false;
        }

        return true;
    }

    public static function edit($params, $uid)
    {
        if (empty($params)) {
            return true;
        }

        $data = [];
        if (isset($params['name'])) {
            $data['name'] = $params['name'];
        }

        if (isset($params['avatar'])) {
            $data['avatar'] = $params['avatar'];
        }

        $adminModel = AdminModel::get(['id' => $uid]);
        if (!$adminModel) {
            throw new AdminException([
                'msg' => '用户不存在或者已删除'
            ]);
        }

        $data['id'] = $uid;
        try {
            $adminModel->save($data);
            return true;
        } catch (\Exception $e){
            return false;
        }
    }

    public static function del($uid)
    {
        $adminModel = new AdminModel();
        $info = $adminModel->getOne(['id' => $uid]);
        if (!$info){
            throw new AdminException([
                'msg' => '你要删除的用户不存在或者已删除'
            ]);
        }

        $res = $info->delete();
        if (!$res){
            Log::error(__METHOD__ . ' 用户删除失败请稍后再试～');
            throw new AdminException([
                'msg' => "用户删除失败请稍后再试～"
            ]);
        }

        return true;
    }

    /**
     * 获取管理员列表
     * @param $page
     * @return array
     */
    public static function getAdminList($page)
    {
        $res = [
            'list' => [],
            'count' => 0,
            'page' => 1,
        ];
        $adminModel = new AdminModel();
        $groupModel = new GroupModel();
        $count = $adminModel->count();
        if ($count == 0){
            return $res;
        }

        $list = $adminModel->getList([], $page)->toArray();
        $group_ids = array_column($list, 'group_id');
        $group_list = $groupModel->getList(['id' => [ 'in', $group_ids]])->toArray();
        $group_list = array_column($group_list, 'name', 'id');
        foreach ($list as &$info){
            if (isset($group_list[$info['group_id']])){
                $info['group_name'] = $group_list[$info['group_id']];
            } else{
                $info['group_name'] = '';
            }

        }

        unset($info);
        $res = [
            'list' => $list,
            'count' => $count,
            'page' => $page,
        ];
        return $res;
    }

    public static function groupAdmin($groupId, $uid)
    {
        $adminModel = new AdminModel();
        $groupModel = new GroupModel();
        $adminInfo = $adminModel->getOne(['id' => $uid]);
        if (!$adminInfo){
            throw new AdminException([
                'msg' => '该用户不存在或者已删除',
            ]);
        }

        $groupInfo = $groupModel->getOne(['id' => $groupId]);
        if (!$groupInfo){
            throw new AuthException([
                'msg' => '该权限分组不存在或者已删除',
            ]);
        }

        $adminInfo->group_id = $groupId;
        try {
            $adminInfo->save();
        } catch (\Exception $e){
            throw new AuthException([
                'msg' => '权限分配失败，请稍后再试'
            ]);
        }

        return true;
    }

    public static function search($key, $page)
    {
        $res = [
            'list' => [],
            'count' => 0,
            'page' => 1,
        ];
        $adminModel = new AdminModel();
        $groupModel = new GroupModel();
        $count = $adminModel->where('email', 'like', "%{$key}%")
            ->whereOr('name', 'like', "%$key%")->count();
        if ($count == 0){
            return $res;
        }

        $list = $adminModel->where('email', 'like', "%{$key}%")
            ->whereOr('name', 'like', "%$key%")
            ->page($page)
            ->select()->toArray();
        $group_ids = array_column($list, 'group_id');
        $group_list = $groupModel->getList(['id' => [ 'in', $group_ids]])->toArray();
        $group_list = array_column($group_list, 'name', 'id');
        foreach ($list as &$info){
            $info['group_name'] = $group_list[$info['group_id']];
        }

        unset($info);
        $res = [
            'list' => $list,
            'count' => $count,
            'page' => $page,
        ];
        return $res;
    }
}