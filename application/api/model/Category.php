<?php
/**
 * User: ruoning
 * Date: 2021/3/14
 * motto: 知行合一!
 */


namespace app\api\model;



class Category extends BaseModel
{
    public function getCategoryList($condition = [], $field = true)
    {
        return $this->where($condition)->field($field)->order('sort desc')->select();
    }

    public function getCategoryListByCids(array $cids, $field = true)
    {
        return $this->where('id', 'in', $cids)->field($field)->select();
    }
}