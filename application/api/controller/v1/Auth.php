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
use app\api\validate\AuthIdCheck;
use app\api\validate\AuthNameCheck;
use app\exception\AuthException;
use app\exception\ParamException;
use app\exception\SucceedMessage;
use think\Exception;
use think\response\Json;

class Auth extends BaseController
{
    public $beforeActionList = [
        'checkAuth'
    ];

    /**
     * 获取权限列表
     * @return Json
     */
    public function authList() {
        $list = AuthService::getAuthList();
        return json($list);
    }

    /**
     * 添加权限列表
     * @throws AuthException
     * @throws SucceedMessage
     * @throws ParamException
     * @throws Exception
     */
    public function add()
    {
        (new AuthNameCheck())->goCheck();
        $name = $this->request->param('name');
        $model_name = $this->request->param('model_name');
        $res = AuthService::addAuth($name, $model_name);
        if ($res){
            throw new SucceedMessage([
             'msg' =>  '权限添加成功'
            ]
            );
        } else {
            throw new AuthException();
        }
    }


    public function update()
    {
        (new AuthCheck())->goCheck();
        $params = $this->request->param();
        $id = $params['auth_id'];
        $res = AuthService::update($id, $params);
        if ($res){
            throw new SucceedMessage([
                'msg' => '权限更新成功'
            ]);
        } else{
            throw new AuthException([
                'msg' => '权限更新异常，请稍后再试~'
            ]);
        }
    }

    public function delete(){
        (new AuthIdCheck())->goCheck();
        $authId = $this->request->param('id');
        $res = AuthService::del($authId);
        if ($res){
            throw new SucceedMessage();
        }
    }
}