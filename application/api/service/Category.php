<?php
/**
 * User: ruoning
 * Date: 2021/3/14
 * motto: 知行合一!
 */


namespace app\api\service;
use app\api\model\Category as CategoryModel;
use app\api\model\UserCategory;
use think\Log;

class Category
{
    public static function getCategoryList()
    {
        $category_model = new CategoryModel();
        return $category_model->getCategoryList([], ['id', 'cname']);
    }

    public static function setUserCategory($uid, array $cids)
    {
        $category_model = new CategoryModel();
        $category_list = $category_model->getCategoryListByCids($cids, ['id', 'cname']);
        $data = [];
        foreach ($category_list as $item) {
            $data[] = [
                'uid' => $uid,
                'cid' => $item['id'],
                'cname' => $item['cname'],
            ];
        }

        $user_category_model = new UserCategory();
        $res = $user_category_model->insertAll($data);
        if (!$res) {
            Log::error("user category add fail uid:{$uid} cids:" . json($cids));
            return false;
        }

        return true;
    }

    public static function delUserCategory($uid, $cid)
    {
        $user_category_model = new UserCategory();
        $res = $user_category_model->where(['uid' => $uid, 'cid' => $cid])->delete();
        if (!$res) {
            Log::info("user category del fail uid:{$uid} cid:{$cid}");
            return false;
        }

        return true;
    }
}
