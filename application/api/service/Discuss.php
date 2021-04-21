<?php


namespace app\api\service;

use app\api\model\User as UserModel;
use app\api\model\News as NewsModel;
use app\exception\NewException;
use app\api\model\Discuss as DiscussModel;
use think\Log;

class Discuss
{
    public static function addDiscuss($uid, $news_id, $content)
    {
        $news_model = new NewsModel();
        $news_info = $news_model->where(['id' => $news_id])->find();
        if (!$news_info) {
            throw new NewException();
        }

        $data = [
            'uid' => $uid,
            'news_id' => $news_id,
            'content' => $content
        ];
        $discuss_model = new DiscussModel();
        $res = $discuss_model->insert($data);
        if (!$res) {
            Log::error("insert discuss fail uid:{$uid} news_id:{$news_id} content:{$content}");
            return false;
        }

        return true;
    }

    public static function discussUpvote($discuss_id, $news_id)
    {
        $discuss_model = new DiscussModel();
        $info = $discuss_model->where(['id' => $discuss_id, 'news_id' => $news_id])->find();
        if (!$info) {
            return false;
        }

        $res = $discuss_model->where(['id' => $discuss_id, 'news_id' => $news_id])->setInc('upvote');
        if (!$res) {
            Log::info(__METHOD__ . "setInc fail discuss_id:{$discuss_id} news_id:{$news_id}");
            return false;
        }

        return true;
    }

    public static function discussUpvoteDel($discuss_id, $news_id)
    {
        $discuss_model = new DiscussModel();
        $info = $discuss_model->where(['id' => $discuss_id, 'news_id' => $news_id])->find();
        if (!$info) {
            return false;
        }

        $res = $discuss_model->where(['id' => $discuss_id, 'news_id' => $news_id])->setDec('upvote');
        if (!$res) {
            Log::info(__METHOD__ . "setDec fail discuss_id:{$discuss_id} news_id:{$news_id}");
            return false;
        }

        return true;
    }

    public static function getDiscussList($news_id)
    {
        $disscuss_model = new DiscussModel();
        $user_model = new UserModel();
        $list = $disscuss_model->where(['news_id' => $news_id])->select()->toArray();
        $uids = array_column($list, 'uid');
        $user_list = $user_model->where('id', 'in', $uids)->select()->toArray();
        $user_list = array_column($user_list, null, 'id');
        foreach ($list as &$item) {
            if (isset($user_list[$item['uid']])) {
                $item['user_info'] = $user_list[$item['uid']];
            }
            unset($item['uid'], $item['user_info']['id']);
        }

        unset($item);
        return $list;
    }
}