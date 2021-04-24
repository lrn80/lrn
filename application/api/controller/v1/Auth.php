<?php
/**
 * User: ruoning
 * Date: 2021/4/24
 * motto: 知行合一!
 */


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Auth as AuthService;
use app\api\validate\AuthCheck;
use app\api\validate\AuthNameCheck;
use app\exception\AuthException;
use app\exception\SucceedMessage;

class Auth extends BaseController
{
    /**
     * 获取权限列表
     * @return \think\response\Json
     */
    public function authList() {
        $list = AuthService::getAuthList();
        return json($list);
    }

    /**
     * 添加权限列表
     * @throws AuthException
     * @throws SucceedMessage
     * @throws \app\exception\ParamException
     * @throws \think\Exception
     */
    public function add()
    {
        (new AuthNameCheck())->goCheck();
        $name = $this->request->param('name');
        $res = AuthService::addAuth($name);
        if ($res){
            throw new SucceedMessage(
                '权限添加成功'
            );
        } else {
            throw new AuthException();
        }
    }


    public function update()
    {
        (new AuthCheck())->goCheck();
        $params = $this->request->param();
        $id = $params['id'];
        $name = $params['name'];
        $res = AuthService::update($id, $name);
        if ($res){
            throw new SucceedMessage([
                'msg' => '权限添加成功'
            ]);
        } else{
            throw new AuthException([
                'msg' => '权限添加异常，请稍后再试~'
            ]);
        }
    }
}