<?php
/**
 * User: ruoning
 * Date: 2021/3/14
 * motto: 知行合一!
 */


namespace app\api\model;


use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class News extends BaseModel
{
    public function getNewsList($condition = [], $page = 1, $limit = 10, $field = [])
    {
        try {
            return $this->where($condition)->limit($limit)->page($page)->field($field)->select();
        } catch (DataNotFoundException $e) {
            throw $e;
        }
    }

    public function getNewsInfo($condition)
    {
        return $this->where($condition)->find();
    }

    public function upvote($condition)
    {
        return $this->where($condition)->setInc('upvote');
    }

    public function delUpvote($condition)
    {
        return $this->where($condition)->setDec('upvote');
    }

    public function getNewsListByCondition($condition, $page = 1, $limit = 10) {
        return $this->where($condition)->page($page)->limit($limit)->select();
    }
}