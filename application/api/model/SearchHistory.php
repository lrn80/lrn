<?php


namespace app\api\model;


class SearchHistory extends BaseModel
{
    public function saveSearchHistory($data)
    {
        return $this->save($data);
    }

}