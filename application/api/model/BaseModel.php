<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/5/31
 * Time: 22:07
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    protected $user_id = 1;
    protected function  prefixImgUrl($value){
        $finalUrl = $value;
        $fromcome = substr($value,0,4);
        if ($fromcome != 'http') {
            $finalUrl = config('setting.img_prefix') . $value;
        }
        return $finalUrl;
    }

}