<?php
/**
 * User: ruoning
 * Date: 2021/3/13
 * motto: 知行合一!
 */


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Email as EmailService;
use app\api\validate\EmailCheck;
use app\exception\EmailException;
use app\api\service\User as UserService;
use app\exception\UserExtistException;
use app\api\service\Admin as AdminService;
use app\lib\exception\ParameterException;
use think\Exception;
use think\response\Json;

class Email extends BaseController
{
    /**
     * 获取验证码
     * @return Json
     * @throws EmailException
     * @throws ParameterException
     * @throws Exception
     */
    public function getCode()
    {
        (new EmailCheck())->goCheck();
        $params = $this->request->param();
        $email = $params['email'] ?? '';
        if (AdminService::checkAdminExist($email)) {
            throw new UserExtistException();
        }
        $code = EmailService::sendCode($params);
        if (!$code) {
            throw new EmailException();
        }

        return json(['code' => $code]);
    }
}
