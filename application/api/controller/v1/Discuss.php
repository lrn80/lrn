<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\DiscussIdCheck;
use app\api\validate\NewsIdCheck;
use app\api\validate\UidNewsIDContentCheck;
use app\api\service\Token;
use app\api\service\Discuss as DiscussService;
use app\exception\DiscussException;
use app\exception\NewException;
use app\exception\SucceedMessage;

class Discuss extends BaseController
{
    /**
     * 添加评论
     */
    public function discuss() {
        (new UidNewsIDContentCheck())->goCheck();
        $uid = Token::getCurrentTokenVar('id');
        $params = $this->request->param();
        $news_id = $params['news_id'] ?? '';
        $content = $params['content'] ?? '';
        $res = DiscussService::addDiscuss($uid, $news_id, $content);
        if ($res) {
            throw new SucceedMessage();
        } else {
            throw new NewException();
        }
    }

    /**
     * 评论点赞
     * @throws DiscussException
     * @throws SucceedMessage
     * @throws \app\exception\ParamException
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function discussUpvote() {
        (new DiscussIdCheck())->goCheck();
        $uid = Token::getCurrentTokenVar('id');
        $params = $this->request->param();
        $news_id = $params['news_id'] ?? '';
        $discuss_id = $params['discuss_id'] ?? '';
        $res = DiscussService::discussUpvote($discuss_id, $news_id);
        if ($res) {
            throw new SucceedMessage();
        } else {
            throw new DiscussException();
        }
    }

    public function discussUpvoteDel() {
        (new DiscussIdCheck())->goCheck();
        $uid = Token::getCurrentTokenVar('id');
        $params = $this->request->param();
        $news_id = $params['news_id'] ?? '';
        $discuss_id = $params['discuss_id'] ?? '';
        $res = DiscussService::discussUpvoteDel($discuss_id, $news_id);
        if ($res) {
            throw new SucceedMessage();
        } else {
            throw new DiscussException();
        }
    }

    /**
     * 获取评论列表
     * @return \think\response\Json
     * @throws \app\exception\ParamException
     */
    public function discussList() {
        (new NewsIdCheck())->goCheck();
        $news_id = $this->request->param('news_id');
        $list = DiscussService::getDiscussList($news_id);
        return json($list);
    }
}