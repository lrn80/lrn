<?php
/**
 * User: ruoning
 * Date: 2021/3/14
 * motto: 知行合一!
 */


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Category as CategoryService;
use app\api\validate\CategoryIdCheck;
use app\api\validate\UserAddCategoryException;
use app\api\validate\UserCategoryCheck;
use app\api\service\Token;
use app\exception\DeleteUserCategoryException;
use app\exception\SucceedMessage;
use think\Log;

class Category extends BaseController
{
    /**
     * 获取分类列表
     * @return \think\response\Json
     */
    public function getCategoryList()
    {
        return json(CategoryService::getCategoryList());
    }

    /**
     * 用户添加频道
     * @throws SucceedMessage
     * @throws UserAddCategoryException
     * @throws \app\exception\ParamException
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function userCategory()
    {
        (new UserCategoryCheck())->goCheck();
        $uid = Token::getCurrentTokenVar('id');
        $cids = explode(',', $this->request->param('cids'));
        $res = CategoryService::setUserCategory($uid, $cids);
        if ($res) {
            throw new SucceedMessage();
        } else {
            throw new UserAddCategoryException();
        }
    }

    /**
     * 用户频道删除
     * @throws DeleteUserCategoryException
     * @throws SucceedMessage
     * @throws \app\exception\ParamException
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function userCategoryDel() {
        (new CategoryIdCheck())->goCheck();
        $uid = Token::getCurrentTokenVar('id');
        $params = $this->request->post();
        $res = CategoryService::delUserCategory($uid, $params['cid']);
        if ($res) {
            throw new SucceedMessage();
        } else {
            throw new DeleteUserCategoryException();
        }
    }

}

