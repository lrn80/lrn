<?php
/**
 * User: ruoning
 * Date: 2021/3/13
 * motto: 知行合一!
 */


namespace app\api\controller;

use app\api\model\Auth;
use app\api\service\Token;
use app\exception\AuthException;
use think\Controller;

class BaseController extends Controller
{
    protected $user = [];

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function authList() {
        return Token::getCurrentTokenVar('auth');
    }

    public function checkAuth(){
        $authList = $this->authList();
        $auth_ids = array_column($authList, 'auth_id');
        $controller = request()->controller();
        list($version, $controller) = explode('.', $controller);
        $auth_info = (new Auth())->getOne(['controller_name' => $controller]);
        if (!$auth_info || !in_array($auth_info['id'], $auth_ids)) {
            throw new AuthException([
                'msg' => 'sorry, 你没有该模块的权限。'
            ]);
        }
    }
}