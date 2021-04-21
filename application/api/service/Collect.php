<?php


namespace app\api\service;
use app\api\model\Category as CategoryModel;
use app\api\model\UserCollectNews;
use app\exception\CollectExtistException;
use think\Log;
use app\api\model\News;
class Collect
{
    public static function userCollect($uid, $news_id)
    {
        $news_model = new News();
        $news_info = $news_model->where(['id' => $news_id])->find();
        if (empty($news_info)) {
            return false;
        }

        $data = [
            'uid' => $uid,
            'news_id' => $news_info['id'],
            'title' => $news_info['title']
        ];
        try {
            $res = (new UserCollectNews())->insert($data);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . "重复收藏 uid:{$uid} news_id:{$news_id}");
            throw new CollectExtistException();
        }

        if (!$res) {
            Log::error(__METHOD__ . "user collect news fail uid:{$uid} news_id:{$news_id}");
            return false;
        }

        return true;
    }

    public static function delCollect($uid, $news_id)
    {
        $data = [
            'uid' => $uid,
            'news_id' => $news_id
        ];
        $res = (new UserCollectNews())->where($data)->delete();
        if (!$res) {
            Log::error(__METHOD__ . "del collect news fail uid:{$uid} news_id:{$news_id}");
            return false;
        }

        return true;
    }

    public static function collectList($uid)
    {
        $collect_model = new UserCollectNews();
        $news_model = new News();
        $collect_list = $collect_model->where(['uid' => $uid])->select()->toArray();
        $news_ids = array_column($collect_list, 'news_id');
        $news_list = $news_model->where('id', 'in', $news_ids)->select()->toArray();
        $news_list = array_column($news_list, null, 'id');
        $collect_list_count = $collect_model->where('news_id', 'in', $news_ids)->field('news_id,count(*) as total')->group('news_id')->select()->toArray();
        $news_ids_count = array_column($collect_list_count, 'total', 'news_id');
        foreach ($news_list as &$news_info) {
            $news_info['collect_sum'] = $news_ids_count[$news_info['id']]['total'] ?? 0;
        }

        unset($news_info);
        return $news_list;
    }
}
