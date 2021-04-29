<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Group as GroupService;
class Group extends BaseController
{
    public function groupList()
    {
        $list = GroupService::getGroupList();
        return json($list);
    }
}