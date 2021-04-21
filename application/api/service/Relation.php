<?php


namespace app\api\service;


use app\api\model\UserRelation;
use app\exception\FollowExtistException;
use app\exception\UserNotExtistException;
use think\Exception;
use think\Log;
use app\api\model\User;
class Relation
{
    const FOLLOW = 1; //关注
    const FANS = 2; // 粉丝
    public static function follow($uid, $follower_id)
    {
        self::checkUser($follower_id); // 查询用户是否存在
        $relation_model = new UserRelation();
        $relation_model->startTrans();
        try {
            $follow_res = self::toFollow($uid, $follower_id);
            if (!$follow_res) {
                $relation_model->rollback();
                return false;
            }

            $fans_res = self::toFans($uid, $follower_id);
            if (!$fans_res) {
                $relation_model->rollback();
                return false;
            }

            $relation_model->commit();
            return true;
        } catch (\Exception $e) {
            Log::error("follow fail ErrorMsg:" . $e->getMessage());
            $relation_model->rollback();
            throw new FollowExtistException();
        }

    }

    protected static function toFollow($uid, $follower_id) {
        $relation_model = new UserRelation();
        $data_follow = [
            'uid' => $uid,
            'follower_id' => $follower_id,
            'relation_type' => self::FOLLOW
        ];
        $relation_info = $relation_model->where($data_follow)->find();
        if ($relation_info) {
            throw new FollowExtistException();
        }
        $res = $relation_model->insert($data_follow);
        if (!$res) {
            Log::error("user relation follow fail uid:{$uid} follower_id:{$follower_id}");
            return false;
        }

        return true;
    }


    protected static function toFans($uid, $follower_id) {
        $relation_model = new UserRelation();
        $data_fans = [
            'uid' => $follower_id,
            'follower_id' => $uid,
            'relation_type' => self::FANS
        ];
        $relation_info = $relation_model->where($data_fans)->find();
        if ($relation_info) {
            throw new FollowExtistException();
        }

        $res = $relation_model->insert($data_fans);
        if (!$res) {
            Log::error("user relation fans fail uid:{$uid} follower_id:{$follower_id}");
            return false;
        }

        return true;
    }

    // 检查用户是否存在
    private static function checkUser($uid)
    {
        $user_model = new User();
        $user_info = $user_model->find($uid);
        if (!$user_info) {
            throw new UserNotExtistException();
        }

        return true;
    }

    public static function getFollowList($uid)
    {
        $relation_model = new UserRelation();
        $user_model = new User();
        $condition = [
            'uid' => $uid,
            'relation_type' => self::FOLLOW,
        ];
        $follow_list = $relation_model->where($condition)->select()->toArray();
        $follower_ids = array_column($follow_list, 'follower_id');
        return $user_model->where('id', 'in', $follower_ids)->field('id,username,avatar')->select();
    }

    public static function getFansList($uid)
    {
        $relation_model = new UserRelation();
        $user_model = new User();
        $condition = [
            'uid' => $uid,
            'relation_type' => self::FANS,
        ];
        $fans_list = $relation_model->where($condition)->select()->toArray();
        $fans_ids = array_column($fans_list, 'follower_id');
        return $user_model->where('id', 'in', $fans_ids)->field('id,username,avatar')->select();
    }
}
