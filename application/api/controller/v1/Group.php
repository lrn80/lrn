<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Group as GroupService;
use app\api\validate\AuthIdAndGroupIdCheck;
use app\api\validate\IDCheck;
use app\exception\SucceedMessage;

class Group extends BaseController
{
    public function groupList()
    {
        $list = GroupService::getGroupList();
        return json($list);
    }

    /**
     *
     */
    public function authToGroup(){
        (new AuthIdAndGroupIdCheck())->goCheck();
        $auth_ids = $this->request->param('auth_ids');
        $group_id = $this->request->param('group_id');
        $res = GroupService::authIdToGroupId($auth_ids, $group_id);
        if ($res){
            throw new SucceedMessage([
                'msg' => '添加分组成功',
            ]);
        }
    }

    public function groupAuthList()
    {
        $list = GroupService::groupAuthList();
        return json($list);
    }

    public function getGroupInfo(){
        (new IDCheck())->goCheck();
        $group_id = $this->request->param('id');
        $list = GroupService::groupInfo($group_id);
        return json($list);
    }
}