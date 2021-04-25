<?php


namespace app\api\service;
use app\api\model\Books as BooksModel;
use app\exception\BooksException;
use think\Log;

class Books
{
    static  $updateParams = [
        'bname', 'author', 'price', 'total_stock', 'now_stock', 'in_library_time',
        'b_no'
    ];

    public static function getBooksList($page)
    {
        $booksModel = new BooksModel();
        return $booksModel->getList([], $page);
    }

    public static function addBooks($params)
    {
        $data = [
            'bname' => $params['bname'],
            'author' => $params['author'],
            'price' => $params['price'] * 100,
            'total_stock' => $params['total_stock'],
            'now_stock' => $params['total_stock'],
            'in_library_time' => $params['in_library_time'] ?? date('Y-m-d H:i:s'),
            'b_no' => $params['b_no'],
        ];

        $booksModel = new BooksModel();
        $res = $booksModel->insert($data);
        if (!$res){
            Log::error(__METHOD__ . ' 图书入库失败 data:' . json_encode($data));
            throw new BooksException([
                'msg' => "图书入库失败～"
            ]);
        }

        return true;
    }

    public static function updateBooks($id, $params)
    {
        $booksModel = new BooksModel();
        $info = $booksModel->getOne(['id' => $id]);
        if (!$info){
            throw new BooksException([
                'msg' => '需要更新的图书不存在或者已删除'
            ]);
        }

        $data = [];
        $data['id'] = $id;
        foreach ($params as $k => $v) {
            if (in_array($k, self::$updateParams)){
                $data[$k] = $v;
            }
        }

        $res = $booksModel->updateData($data);
        if (!$res){
            Log::error(__METHOD__ . ' 更新图书管理数据失败 data: ' . json_encode($data));
            throw new BooksException([
                'msg' => '更新图书管理数据失败，请稍后再试～'
            ]);
        }

        return true;
    }

    public static function search($key, $page)
    {
        $booksModel = new BooksModel();
        return $booksModel->where('bname', 'like', "%{$key}%")
                   ->whereOr('author', 'like', "%$key%")
                   ->page($page)
                   ->select();
    }
}