<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Token;
use app\api\validate\NewsIdCheck;
use app\api\service\Collect as CollectService;
use app\exception\SucceedMessage;
use app\exception\UserCollectException;

class Collect extends BaseController
{
    /**
     * 用户收藏接口
     * @throws SucceedMessage
     * @throws UserCollectException
     * @throws \app\exception\ParamException
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function userCollect()
    {
        (new NewsIdCheck())->goCheck();
        $uid = Token::getCurrentTokenVar('id');
        $params = $this->request->param();
        $res = CollectService::userCollect($uid, $params['news_id']);
        if ($res) {
            throw new SucceedMessage();
        } else {
            throw new UserCollectException();
        }
    }

    /**
     * 取消收藏
     * @throws SucceedMessage
     * @throws UserCollectException
     * @throws \app\exception\ParamException
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function userCollectDel()
    {
        (new NewsIdCheck())->goCheck();
        $uid = Token::getCurrentTokenVar('id');
        $params = $this->request->param();
        $res = CollectService::delCollect($uid, $params['news_id']);
        if ($res) {
            throw new SucceedMessage();
        } else {
            throw new UserCollectException();
        }
    }

    /**
     * 收藏列表
     * @return \think\response\Json
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function collectList() {
        $uid = Token::getCurrentTokenVar('id');
        $list = CollectService::collectList($uid);
        return json($list);
    }
}
