<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\BnoStIdCheck;
use app\api\validate\PageParamCheck;
use app\api\service\Borrow as BorrowService;
use app\api\validate\SearchCheck;
use app\exception\SucceedMessage;

class Borrow extends BaseController
{
    public $beforeActionList = [
        'checkAuth'
    ];
    /**
     * 获取借书列表
     * @return \think\response\Json
     * @throws \app\exception\ParamException
     */
    public function borrowList(){
        (new PageParamCheck())->goCheck();
        $page = $this->request->param('page');
        $list = BorrowService::getBorrowList($page);
        return json($list);
    }

    public function leadBook(){
        (new BnoStIdCheck())->goCheck();
        $b_no = $this->request->param('b_no');
        $st_id = $this->request->param('st_id');
        $res = BorrowService::leadBook($b_no, $st_id);
        if ($res) {
            throw new SucceedMessage([
                'msg' => '借书成功'
            ]);
        }
    }

    public function search()
    {
        (new SearchCheck())->goCheck();
        $key = $this->request->param('key');
        $page = $this->request->param('page') ?? 1;
        $list = BorrowService::search($key, $page);
        return json($list);
    }
}