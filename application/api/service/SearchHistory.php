<?php


namespace app\api\service;
use \app\api\model\SearchHistory as SearchHistoryModel;
use think\Log;

class SearchHistory
{
    public static function getSearchHistoryList($uid)
    {
        $search_history_model = new SearchHistoryModel();
        return $search_history_model->where(['uid' => $uid])->field(['key'])->distinct('key')->select();
    }

    public static function delHistory($uid)
    {
        $search_history_model = new SearchHistoryModel();
        $res = $search_history_model->where(['uid' => $uid])->delete();
        if (!$res) {
            Log::error("delete uid:{$uid} search history fail");
            return false;
        }

        return $res;
    }
}