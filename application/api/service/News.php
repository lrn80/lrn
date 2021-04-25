<?php
/**
 * User: ruoning
 * Date: 2021/3/14
 * motto: 知行合一!
 */


namespace app\api\service;
use app\api\model\News as NewsModel;
use app\api\model\SearchHistory;
use app\api\model\User;
use app\api\model\UserCollectNews;
use app\api\validate\NewsPageCheck;
use think\Log;

class News
{
    public static function getNewsList($params)
    {
        $news_list = [];
        $page = $params['page'];
        $news_model = new NewsModel();
        if (isset($params['cid'])) {
            $news_list = $news_model->getNewsList(['cid' => $params['cid']], $page);
        } else {
            $news_list = $news_model->getNewsList([], $page);
        }

        return $news_list;
    }

    public static function getNewsInfo($params)
    {
        $news_id = $params['news_id'];
        $news_model = new NewsModel();
        $collect_num = (new UserCollectNews())->where(['news_id' => $news_id])->count();
        $data = $news_model->getNewsInfo(['id' => $news_id]);
        $data['collect_num'] = $collect_num;
        //$user_model = new User();
        //$user_info = $user_model->getUserByCondition(['uid' => $news_info['uid']], ['name', 'id']);
        return $data;
    }

    public static function upvote($uid)
    {
        $news_model = new NewsModel();
        $news_info = $news_model->getNewsInfo(['id' => $uid]);
        if (empty($news_info)) {
            return [];
        }

        $succ = $news_model->upvote(['id' => $uid]);
        if ($succ) {
            return ['upvote' => $news_info['upvote']];
        }

        return false;
    }

    public static function delUpvote($uid)
    {
        $news_model = new NewsModel();
        $news_info = $news_model->getNewsInfo(['id' => $uid]);
        if (empty($news_info)) {
            return [];
        }

        $succ = $news_model->delUpvote(['id' => $uid]);
        if ($succ) {
            return ['upvote' => $news_info['upvote']];
        }

        return false;
    }

    public static function search($params)
    {
        $page = $params['page'];
        $news_model = new NewsModel();
        $search_history_model = new SearchHistory();
        $data = [
            'uid' => $params['uid'],
            'key' => $params['key'],
        ];
        $res = $search_history_model->saveSearchHistory($data);
        if (!$res) {
            Log::error(__METHOD__ . "insert search_history fail data:" . json_encode($data));
        }

        $condition = [
            'title' => ['like', '%' . $params['key'] . '%'],
        ];

        return $news_model->getNewsListByCondition($condition, $page);
    }

    public static function getUserNewsList($uid, $page)
    {
        $news_model = new NewsModel();
        $conditions = [
            'uid' => $uid,
        ];
        $list = $news_model->getNewsList($conditions, $page);
        return $list;
    }
}
