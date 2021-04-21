<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/4/20
 * Time: 17:01
 */

namespace app\api\controller\v1;

use app\api\service\TokenUser;
use app\api\validate\LoginCheck;
use app\api\validate\RegisterCheck;
use app\exception\LoginException;
use app\exception\RegisterException;
use app\exception\SucceedMessage;
use app\exception\UserException;
use app\exception\UserExtistException;
use think\Cache;
use think\Env;
use think\Exception;
use think\Request;
use app\api\service\User as UserService;
use app\api\service\Email;
use app\api\service\Token;
class User {
    protected $user;

    /**
     * 用户登陆接口
     * @throws Exception
     * @throws LoginException
     * @throws SucceedMessage
     * @throws \app\exception\ParamException
     */
    public function login() {
        (new LoginCheck())->goCheck();
        $params = request()->param();
        $user_info = UserService::find($params);
       //$user_info->getAttr('avatar');
        if (!$user_info) {
            throw new LoginException();
        }

        return json($user_info);
    }

    public function logout() {
        $token  = Request::instance()->header("token");
        $result = Cache::rm($token);
        if ($result) {
            throw new SucceedMessage();
        } else {
            throw new Exception('退出异常');
        }
    }

    /**
     * 用户注册接口
     * @throws Exception
     * @throws RegisterException
     * @throws SucceedMessage
     * @throws UserException
     * @throws \app\exception\ParamException
     */
    public function register() {
        (new RegisterCheck())->goCheck();
        if ($_POST['password'] !== $_POST['re_password']){
            throw new RegisterException([
                'msg' => '两次密码不一致'
            ]);
        }
        $params = request()->param();
        $verify = Email::verifyCode($params);
       if (!$verify) {
            throw new RegisterException([
                'msg' => '验证码错误'
            ]);
        }

        if ($this->checkUserExist($params['email'])) {
            throw new UserExtistException();
        }

        $succ = UserService::saveUserInfo($params);
        if ($succ){
            throw new SucceedMessage();
        }else{
            throw new UserException();
        }
    }

    public function getUserInfo() {
        return TokenUser::getInfoByTokenVar();
    }

    protected function checkUserExist($email) {
        $user_info = UserService::getUserInfoByCondition(['email' => $email]);
        if ($user_info) {
            return true;
        }

        return false;
    }

    /**
     * 点赞总数
     * @return \think\response\Json
     * @throws Exception
     * @throws \app\exception\TokenException
     */
    public function upVoteSum()
    {
        $uid = Token::getCurrentTokenVar('id');
        $res = UserService::upVoteSum($uid);
        return json($res);
    }


}
