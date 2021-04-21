<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/4/14
 * Time: 12:07
 */

namespace app\api\validate;


use app\exception\ParamException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        $param = Request::instance()->param();
        if(!$this->batch()->check($param)){
            throw new ParamException([
                'msg' => $this->getError(),
            ]);
        }
        return true;
    }
    protected function isNotEmpty($value){
        if (empty($value)) {
            return false;
        } else {
            return true;
        }
    }
    protected function isMustInteger($value){
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return false;
    }
    protected function checkDateFormat($value)
    {
        // 首先是验证日期的一般格式
        if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $value, $parts)) {
            if (checkdate( $parts[2], $parts[3], $parts[1])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    protected function checkMonthFormat($value)
    {
        // 首先是验证月份的一般格式
        if (preg_match("/^([0-9]{4})-([0-9]{2})$/", $value, $parts)) {
            return true;
        } else {
            return false;
        }
    }
    public function getDateByRule($array){
        if(array_key_exists('user_id',$array)&&array_key_exists('uid',$array)){
            throw new ParamException([
                'msg' => '请求中有非法请求参数uid或user_id',
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value){
            $newArray[$key] = $array[$key];
        }
        return $newArray;
    }
    protected function checkInformation($value){
        if(strcmp($value, 'month')==0||strcmp($value, 'week')==0){
            return true;
        }else{
            return false;
        }
    }

    //验证提交的规范，比如出现脏话痞话的字，禁止提交。
    protected function checkStandard($value){
        $shield = '她妈|它妈|他妈|你妈|妈的|去死|贱人|1090tv|10jil|21世纪中国基金会
        |2c8|3p|4kkasi|64惨案|64惨剧|64学生运动|64运动|64运动民國|89惨案|89惨剧
        |89学生运动|89运动|adult|asiangirl|avxiu|av女|awoodong|A片|bbagoori|bbagury
        |bdsm|binya|bitch|bozy|bunsec|bunsek|byuntae|B样|fa轮|fuck|ＦｕｃΚ|gay|hrichina
        |jiangzemin|j女|kgirls|kmovie|lihongzhi|MAKELOVE|NND|nude|petish|playbog|playboy
        |playbozi|pleybog|pleyboy|q奸|realxx|s2x|sex|shit|sorasex|tmb|TMD|tm的
        |tongxinglian|triangleboy|UltraSurf|unixbox|ustibet|voa';

        $arr = explode('|', $shield);
        foreach ($arr as $v){
            if (stripos($value, $v) !== false){
                return false;
            }
        }
        return true;
    }
    //验证提交的规范，比如出现脏话痞话的字，禁止提交。
    protected function listVarString($value){
       $arr = array('inter','tradi','resear','chinaground');
        foreach ($arr as $v){
            if (!(strcasecmp($value, $v))){
                return true;
                break;
            }
        }
        return false;
    }
}
