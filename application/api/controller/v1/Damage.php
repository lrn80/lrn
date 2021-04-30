<?php


namespace app\api\controller\v1;

use app\api\service\Damage as DamageService;
use app\api\controller\BaseController;
use app\api\validate\DamageCheck;
use app\api\validate\IDCheck;
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

    /**
     * 增加破损订单
     * @throws SucceedMessage
     * @throws \app\exception\DamageException
     * @throws \app\exception\ParamException
     */
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

    /**
     * 订单修复
     * @throws SucceedMessage
     * @throws \app\exception\DamageException
     * @throws \app\exception\ParamException
     */
    public function repair(){
        (new IDCheck())->goCheck();
        $id = $this->request->param('id');
        $res = DamageService::repair($id);
        if ($res){
            throw new SucceedMessage([
                'msg' => '破损订单数据修改成功'
            ]);
        }
    }
}