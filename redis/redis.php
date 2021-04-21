<?php
include "PdoObj.php";
$pdo=PdoObj::getPDO();
//将pv量写入数据库
$sqlArticle=$pdo->prepare("update article set pv=:articlepv where id=:articleid");
$sqlArticle->bindParam(':articlepv',$ar);
$sqlArticle->bindParam(':articleid',$art);
    $sqlArticle->bindParam(':articlepv',$pv,PDO::PARAM_STR);
    $sqlArticle->bindParam(':articleid',$arr,PDO::PARAM_STR);
    print_r($sqlArticle->execute());
