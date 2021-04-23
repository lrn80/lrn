<?php


namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Email;
use app\api\validate\AdminRegisterCheck;
use app\api\validate\LoginCheck;
use app\exception\AdminException;
use app\exception\LoginException;
use app\exception\RegisterException;
use app\exception\SucceedMessage;
use app\exception\UserException;
use app\exception\UserExtistException;
use app\api\service\Admin as AdminService;
class Admin extends BaseController
{
    public function register() {
        (new AdminRegisterCheck())->goCheck();
        if ($_POST['password'] !== $_POST['re_password']){
            throw new AdminException([
                'msg' => '两次密码不一致'
            ]);
        }
        $params = request()->param();
        $verify = Email::verifyCode($params);
        if (!$verify) {
            throw new AdminException([
                'msg' => '验证码错误'
            ]);
        }

        if (AdminService::checkAdminExist(($params['email']))) {
            throw new UserExtistException();
        }

        $succ = AdminService::createAdmin($params);
        if ($succ){
            throw new SucceedMessage();
        }else{
            throw new AdminException();
        }
    }

    public function login() {
        (new LoginCheck())->goCheck();
        $params = request()->param();
        $email = $params['email'];
        $password = $params['password'];
        $user_info = AdminService::login();
        //$user_info->getAttr('avatar');
        if (!$user_info) {
            throw new LoginException();
        }

        return json($user_info);
    }
}