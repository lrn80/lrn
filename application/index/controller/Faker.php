<?php
/**
 * Created by PhpStorm.
 * User: 老王专用
 * Date: 2019/11/12
 * Time: 21:02
 */

namespace app\index\controller;


use Faker\Factory;
use think\Db;

class Faker{
    public function makeFaker(){
        $faker = Factory::create('zh_CN');
        $data = [];
        $uid_name = [
            1=>'lrn',
            2=>'路瑞',
            3=>'史建军',
            4=>'官淑英',
            5=>'植秀华',
            6=>'曹佳',
            7=>'刘龙'
        ];
        for ($i=0; $i<50; $i++){
            $uid = random_int(1, 7);
            $data[] = [
                'title' => $faker->realText(50),
                'uid' => $uid,
                'cid' => rand(1,7),
                'author' => $uid_name[$uid],
                'image_url' => "/upload/user/924e655022aee453710743990c24134c.jpg",
                'content' => $faker->text(1000),
                'upvote' => rand(0,10000),
                'create_time' => $faker->date("Y-m-d H:i:s"),
            ];
        }
        var_dump($data);
        Db::table('news')->insertAll($data);
    }

    public function makeUser()
    {
        $faker = Factory::create('zh_CN');
        $data = [];
        for ($i=0; $i<6; $i++){
            $data[] = [
                'username' => $faker->name,
                'avatar' => "/upload/user/924e655022aee453710743990c24134c.jpg",
                'email' => $faker->email,
                'password' => md5('123456'),
                'introduction' => $faker->realText(50),
            ];
        }
        var_dump($data);
        Db::table('user')->insertAll($data);
    }
}