<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/11/23
 * Time: 11:34
 */

namespace app\index\controller;


use think\Controller;

class Index extends Controller {
    public function Index(){
        return $this->fetch();
    }
}