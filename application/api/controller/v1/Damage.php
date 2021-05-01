<?php


namespace app\api\controller\v1;

use app\api\service\Damage as DamageService;
use app\api\controller\BaseController;
use app\api\validate\DamageCheck;
use app\api\validate\IDCheck;
use app\api\validate\PageParamCheck;
use app\api\validate\SearchCheck;
use app\api\validate\StatusCheck;
use app\exception\DamageException;
use app\exception\ParamException;
use app\exception\SucceedMessage;
use think\Exception;
use think\response\Json;

class Damage extends BaseController
{
    public $beforeActionList = [
        'checkAuth'
    ];

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
     * @throws DamageException
     * @throws ParamException
     * @throws Exception
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
     * @throws DamageException
     * @throws ParamException
     * @throws Exception
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

    /**
     * @return Json
     * @throws ParamException
     */
    public function search(){
        (new SearchCheck())->goCheck();
        $key = $this->request->param('key');
        $page = $this->request->param('page') ?? 1;
        $list = DamageService::search($key, $page);
        return json($list);
    }
}