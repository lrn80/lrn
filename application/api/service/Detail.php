<?php


namespace app\api\service;
use app\api\model\Books as BooksModel;

class Detail
{
    public static function getDetailInfo($bNo)
    {
        $booksModel = new BooksModel();
        return $booksModel->alias('b')
                           ->join('detail d', 'b.b_no = d.b_no')
                           ->where(['d.b_no' => $bNo])
                           ->field('bname,b.author,b.price,b.b_no,call_number,location,press,status')
                           ->find()->toArray();
    }
}