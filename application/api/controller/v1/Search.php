<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Token;
use app\api\service\SearchHistory as SearchHistoryService;
use app\exception\DelHistoryException;
use app\exception\SucceedMessage;

class Search extends BaseController
{
    /**
     * 获取搜索历史列表
     * @return \think\response\Json
     * @throws \app\exception\TokenException
     * @throws \think\Exception
     */
    public function searchHistory()
    {
        $uid = Token::getCurrentTokenVar('id');
        return json(SearchHistoryService::getSearchHistoryList($uid));
    }

    public function delHistory() {
        $uid = Token::getCurrentTokenVar('id');
        $res = SearchHistoryService::delHistory($uid);
        if (!$res) {
            throw new DelHistoryException();
        } else {
            throw new SucceedMessage();
        }

    }

}