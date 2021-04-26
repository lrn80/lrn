<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\BnoStIdCheck;
use app\api\validate\BooksCheck;
use app\api\validate\BooksUpdateCheck;
use app\api\validate\PageParamCheck;
use app\api\service\Books as BooksService;
use app\api\validate\SearchCheck;
use app\exception\SucceedMessage;

class Books extends BaseController
{
    public $beforeActionList = [
        'checkAuth'
    ];

    /**
     * 获取图书管理模块列表
     * @return \think\response\Json
     * @throws \app\exception\ParamException
     */
    public function getBooksList() {
        (new PageParamCheck())->goCheck();
        $page = $this->request->param('page');
        $list = BooksService::getBooksList($page);
        return json($list);
    }

    /**
     * 增加图书信息
     * @throws SucceedMessage
     * @throws \app\exception\BooksException
     * @throws \app\exception\ParamException
     */
    public function addBooks() {
        (new BooksCheck())->goCheck();
        $params = $this->request->param();
        $res = BooksService::addBooks($params);
        if ($res) {
            throw new SucceedMessage([
                'msg' => '图书入库成功'
            ]);
        }
    }

    /**
     * 更新图书信息
     * @throws SucceedMessage
     * @throws \app\exception\BooksException
     * @throws \app\exception\ParamException
     */
    public function updateBooks() {
        (new BooksUpdateCheck())->goCheck();
        $params = $this->request->param();
        $id = $this->request->param('id');
        $res = BooksService::updateBooks($id, $params);
        if ($res){
            throw new SucceedMessage([
                'msg' => '图书信息更新成功'
            ]);
        }
    }

    /**
     * 搜索
     * @return \think\response\Json
     * @throws \app\exception\ParamException
     */
    public function search(){
        (new SearchCheck())->goCheck();
        $key = $this->request->param('key');
        $page = $this->request->param('page') ?? 1;
        $list = BooksService::search($key, $page);
        return json($list);
    }
}