<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;

class Detail extends BaseController
{
    public $beforeActionList = [
        'checkAuth'
    ];

    public function getBookInfo(){

    }
}