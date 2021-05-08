<?php


namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Admin as AdminService;
use app\api\service\Email;
use app\api\service\Token;
use app\api\service\Upload;
use app\api\validate\AdminCheck;
use app\api\validate\AdminIdCheck;
use app\api\validate\AdminRegisterCheck;
use app\api\validate\GroupIdCheck;
use app\api\validate\LoginCheck;
use app\api\validate\PageParamCheck;
use app\api\validate\SearchCheck;
use app\exception\AdminException;
use app\exception\LoginException;
use app\exception\SucceedMessage;
use app\exception\UserExtistException;

class Admin extends BaseController
{
    public $beforeActionList = [
        'checkAuth' => ['only' => 'delete,edit,adminAuth']
    ];

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
     * @throws \think\Exception
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
     * @throws \think\Exception
     */
    public function edit() {
        (new AdminCheck())->goCheck();
        $uid = Token::getCurrentTokenVar('id');
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

    public function delete(){
        (new AdminIdCheck())->goCheck();
        $uid = $this->request->param('id');
        $res = AdminService::del($uid);
        if ($res){
            throw new SucceedMessage([
                'msg' => '管理员删除成功'
            ]);
        }
    }

    /**
     * 获取管理员列表
     * @return \think\response\Json
     * @throws \app\exception\ParamException
     */
    public function adminList()
    {
        (new PageParamCheck())->goCheck();
        $page = $this->request->param('page') ?? 1;
        $list = AdminService::getAdminList($page);
        return json($list);
    }

    public function adminAuth()
    {
        (new GroupIdCheck())->goCheck();
        $group_id = $this->request->param('group_id');
        $uid = $this->request->param('uid');
        $res = AdminService::groupAdmin($group_id, $uid);
        if ($res){
            throw new SucceedMessage([
                'msg' => '权限分配成功~'
            ]);
        }
    }

    public function search(){
        (new SearchCheck())->goCheck();
        $key = $this->request->param('key');
        $page = $this->request->param('page') ?? 1;
        $list = AdminService::search($key, $page);
        return json($list);
    }
}