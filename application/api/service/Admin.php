<?php


namespace app\api\service;

use app\api\controller\BaseController;
use app\api\service\Admin as AdminService;
use app\api\model\Admin as AdminModel;
use think\Log;

class Admin extends BaseController
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

        $adminInfo = $adminModel->getOne($condition, true);
        if ($adminInfo) {
            return true;
        }

        return false;
    }

    public static function login($email, $password)
    {
        $condition = [
            'email' => $email,
            'password' => $password
        ];
        $userInfo = AdminModel::where($condition)->find();

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
}