<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\BnoStIdCheck;
use app\api\validate\PageParamCheck;
use app\api\service\Borrow as BorrowService;
class Borrow extends BaseController
{
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
    }
}