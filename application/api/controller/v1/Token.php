<?php
/**
 * Created by PhpStorm.
 * User: θηδΈη¨
 * Date: 2019/4/14
 * Time: 12:04
 */

namespace app\api\controller\v1;


use app\api\validate\LoginCheck;
use app\api\validate\TokenGet;
use app\api\service\TokenUser;

class Token
{
    public function getToken(){
        (new TokenGet())->goCheck();
        $params = request()->param();
        $token = (new TokenUser())->get($params['uid']);
        return json(['token' => $token]);
    }
}