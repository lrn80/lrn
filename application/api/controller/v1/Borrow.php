<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\PageParamCheck;

class Borrow extends BaseController
{

    public function borrowList(){
        (new PageParamCheck())->goCheck();

    }
}