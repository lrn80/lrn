<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\BnoStIdCheck;
use app\api\validate\IDCheck;
use app\api\validate\PageParamCheck;
use app\api\service\Borrow as BorrowService;
use app\api\validate\SearchCheck;
use app\api\validate\StatusCheck;
use app\exception\ParamException;
use app\exception\SucceedMessage;

class Borrow extends BaseController
{
    public $beforeActionList = [
        'checkAuth'
    ];
    /**
     * 获取借书列表
     * @return \think\response\Json
     * @throws ParamException
     */
    public function borrowList(){
        (new PageParamCheck())->goCheck();
        (new StatusCheck())->goCheck();
        $page = $this->request->param('page') ?? 1;
        $status = $this->request->param('status');
        $list = BorrowService::getBorrowList($page, $status);
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

    public function returnBook(){
        (new IDCheck())->goCheck();
        $id = $this->request->param('id');
        $res = BorrowService::returnBook($id);
        if ($res){
            throw new SucceedMessage([
                'msg' => '还书成功，再借一本别的书📖？'
            ]);
        }
    }
}