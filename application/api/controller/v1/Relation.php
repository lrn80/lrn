<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\FollowIdCheck;
use app\api\service\Token;
use app\api\service\Relation as RelationService;
use app\exception\FollowException;
use app\exception\FollowExtistException;
use app\exception\SucceedMessage;

class Relation extends BaseController
{
    /**
     * 用户关注接口
     * @throws FollowException
     * @throws SucceedMessage
     * @throws \app\exception\ParamException
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function follow() {
        (new FollowIdCheck())->goCheck();
        $uid = Token::getCurrentTokenVar('id');
        $follower_id = $this->request->param('follow_id');
        $res = RelationService::followDel($uid, $follower_id);
        if ($res) {
            throw new SucceedMessage();
        } else {
            throw new FollowException();
        }
    }

    /**
     * 获取关注列表
     * @return \think\response\Json
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function followList() {
        $uid = Token::getCurrentTokenVar('id');
        return json(RelationService::getFollowList($uid));
    }

    /**
     * 获取粉丝列表
     * @return \think\response\Json
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function fansList() {
        $uid = Token::getCurrentTokenVar('id');
        return json(RelationService::getFansList($uid));
    }

    public function followDel() {
        (new FollowIdCheck())->goCheck();
        $uid = Token::getCurrentTokenVar('id');
        $follower_id = $this->request->param('follow_id');
    }

}