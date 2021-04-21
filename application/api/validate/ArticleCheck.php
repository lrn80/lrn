<?php


namespace app\api\validate;


class ArticleCheck extends BaseValidate
{
       protected $rule=
       [
       'name' => 'require|max:10',
       'email' =>'email',
       ];
}