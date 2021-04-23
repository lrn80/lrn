<?php


namespace app\api\model;


class Admin extends BaseModel
{
    protected $hidden = ['update_time', 'password'];
    public function getAvatarAttr($value) {
        return $this->prefixImgUrl($value);
    }
}