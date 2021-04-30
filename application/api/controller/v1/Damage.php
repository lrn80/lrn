<?php


namespace app\api\controller\v1;

use app\api\service\Damage as DamageService;
use app\api\controller\BaseController;
use app\api\validate\DamageCheck;
use app\api\validate\PageParamCheck;
use app\api\validate\StatusCheck;
use app\exception\SucceedMessage;

class Damage extends BaseController
{

    /**
     * 获得破损 修复列表
     */
    public function damageList()
    {
        (new StatusCheck())->goCheck();
        (new PageParamCheck())->goCheck();
        $status = $this->request->param('status');
        $page = $this->request->param('page');
        return json(DamageService::getDamageList($status, $page));
    }

    public function addDamage()
    {
        (new DamageCheck())->goCheck();
        $params = $this->request->param();
        $res = DamageService::add($params);
        if ($res){
            throw new SucceedMessage([
                'msg' => '破损订单添加成功'
            ]);
        }
    }
}