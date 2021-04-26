<?php


namespace app\api\controller\v1;

use app\api\service\Detail as DetailService;
use app\api\controller\BaseController;
use app\api\validate\BNoCheck;
use app\api\validate\BooksIdCheck;

class Detail extends BaseController
{
    public $beforeActionList = [
        'checkAuth'
    ];

    public function getBookInfo(){
        (new BNoCheck())->goCheck();
        $bNo = $this->request->param('b_no');
        $info = DetailService::getDetailInfo($bNo);
        return json($info);
    }
}