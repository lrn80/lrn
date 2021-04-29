<?php


namespace app\api\service;
use \app\api\model\Group as GroupModel;

class Group
{
    public static function getGroupList()
    {
        $groupModel = new GroupModel();
        return $groupModel->getList([], 0);
    }
}