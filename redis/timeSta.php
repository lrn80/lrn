<?php
include "redisObj.php";
include "switch.php";
include "PdoObj.php";
$redis=Redisobj::getRedis();
$keys=$redis->keys("*");
if (!empty($keys)) {
    $redis->select(5);
    $pdo = PdoObj::getPDO();
//ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
//set_time_limit(0);//设置允许脚本运行的时间，单位为秒。参数值为0时不受限制。
//将pv量写入数据库
    $sqlArticle = $pdo->prepare("update article set pv=:articlepv where id=:articleid");
    $keyArtcile = $redis->keys("article:articleid:*:pv");
    foreach ($keyArtcile as $k => $value) {
        $arr = explode(":", $value);
        $pv = $redis->get("$value");
        $sqlArticle->bindParam(':articlepv', $pv, PDO::PARAM_STR);
        $sqlArticle->bindParam(':articleid', $arr[2], PDO::PARAM_STR);
        $sqlArticle->execute();
        $redis->del($value);
        $redis->del("article:articleid:" . $arr[2] . ":cateid");
    }

//将view量写入数据库
    $sqlCate = $pdo->prepare("update categorys set view=:cateview where id=:cateid");
    $keyCategorys = $redis->keys("categorys:cateid:*:view");
    foreach ($keyCategorys as $k => $value) {
        $arr = explode(":", $value);
        $view = $redis->get("$value");
        $sqlCate->bindParam(':cateview', $view, PDO::PARAM_STR);
        $sqlCate->bindParam(':cateid', $arr[2], PDO::PARAM_STR);
        $sqlCate->execute();
        $redis->del($value);
    }
}
$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if ($switch==1) {
    time_nanosleep(5*60, 10);
    file_get_contents($url);
}

