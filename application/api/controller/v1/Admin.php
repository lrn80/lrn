<?php


namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Email;
use app\api\service\Token;
use app\api\service\Upload;
use app\api\service\User as UserService;
use app\api\validate\AdminCheck;
use app\api\validate\AdminRegisterCheck;
use app\api\validate\LoginCheck;
use app\api\validate\UserCheck;
use app\exception\AdminException;
use app\exception\LoginException;
use app\exception\RegisterException;
use app\exception\SucceedMessage;
use app\exception\UserEditException;
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

    /**
     * 用户登陆
     * @return \think\response\Json
     * @throws LoginException
     * @throws \app\exception\ParamException
     */
    public function login() {
        (new LoginCheck())->goCheck();
        $params = request()->param();
        $email = $params['email'];
        $password = $params['password'];
        $user_info = AdminService::login($email, $password);
        //$user_info->getAttr('avatar');
        if (!$user_info) {
            throw new LoginException();
        }

        return json($user_info);
    }

    /**
     * 用户信息修改
     * @throws AdminException
     * @throws SucceedMessage
     * @throws \app\exception\ParamException
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function edit() {
        (new AdminCheck())->goCheck();
        //$uid = Token::getCurrentTokenVar('id');
        $uid = 1;
        $params = request()->post();
        $save_name = Upload::uploadImg(config('setting.img_url'), 'image');
        if ($save_name != '') {
            $params['avatar'] = '/upload/user/' . $save_name;
        }

        $res = AdminService::edit($params, $uid);
        if ($res) {
            throw new SucceedMessage();
        } else {
            throw new AdminException([
                '用户信息修改失败，请稍后再试～'
            ]);
        }
    }
}