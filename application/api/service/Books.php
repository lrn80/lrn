<?php


namespace app\api\service;
use app\api\model\Books as BooksModel;
use app\api\model\Detail;
use app\exception\BooksException;
use think\Exception;
use think\Log;

class Books
{
    static  $updateParams = [
        'bname', 'author', 'price', 'total_stock', 'now_stock', 'in_library_time',
        //'b_no'
    ];

    static $updateDetail = [
        'call_number', 'location', 'author', 'press', 'status'
    ];

    public static function getBooksList($page)
    {
        $booksModel = new BooksModel();
        $booksList =  $booksModel->getList([], $page);
        $b_nums = array_column($booksList->toArray(), 'b_no');
        $detailModel = new Detail();
        $detailList = $detailModel->getList(['b_no' =>['in', $b_nums]], 0);
        $detailList = array_column($detailList->toArray(), null, 'b_no');
        foreach ($booksList as &$list){
            if (isset($detailList[$list['b_no']])){
                $list['call_number'] = $detailList[$list['b_no']]['call_number'];
                $list['location'] = $detailList[$list['b_no']]['location'];
                $list['press'] = $detailList[$list['b_no']]['press'];
                $list['status'] = $detailList[$list['b_no']]['status'];
            } else {
                $list['call_number'] = '';
                $list['location'] = '';
                $list['press'] = '';
                $list['status'] = '';
            }
        }

        unset($list);
        return $booksList;
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
            'warehose_no' => $params['warehose_no'],
        ];

        $detail_data = [
            'b_no' => $params['b_no'],
            'call_number' => $params['call_number'],
            'location' => $params['location'],
            'author' => $params['author'],
            'press' => $params['press'],
            'status' => $params['status'] ?? 0
        ];

        $booksModel = new BooksModel();
        $detailModel = new Detail();
        $booksModel->startTrans();
        try {
            $res = $booksModel->insert($data);
            if (!$res){
                Log::error(__METHOD__ . ' 图书入库失败 data:' . json_encode($data));
                throw new BooksException([
                    'msg' => "图书入库失败～"
                ]);
            }

            $detailRes = $detailModel->insert($detail_data);
            if (!$detailRes){
                Log::error(__METHOD__ . '  detail 图书入库失败 data:' . json_encode($data));
                throw new BooksException([
                    'msg' => "图书入库失败～"
                ]);
            }

            $booksModel->commit();
        } catch (\Exception $e){
            $booksModel->rollback();
            throw new BooksException([
                'msg' => '图书入库失败，请稍后再试～'
            ]);
        }

        return true;
    }

    public static function updateBooks($id, $params)
    {
        $booksModel = new BooksModel();
        $detailModel = new Detail();
        $info = $booksModel->getOne(['id' => $id]);
        if (!$info){
            throw new BooksException([
                'msg' => '需要更新的图书不存在或者已删除'
            ]);
        }

        $data = [];
        $detailData = ['b_no' => $info['b_no']];
        $data['id'] = $id;
        foreach ($params as $k => $v) {
            if (in_array($k, self::$updateParams)){
                $data[$k] = $v;
            }

            if (in_array($k, self::$updateDetail)){
                $detailData[$k] = $v;
            }
        }

        $booksModel->startTrans();
        try {
            $res = $booksModel->updateData($data);
            if (!$res){
                $booksModel->rollback();
                Log::error(__METHOD__ . ' 更新图书管理数据失败 data: ' . json_encode($data));
                throw new BooksException([
                    'msg' => '更新图书管理数据失败，请稍后再试～'
                ]);
            }

            $detailRes = $detailModel->update($detailData, ['b_no' => $info['b_no']]);
            if (!$detailRes){
                $booksModel->rollback();
                Log::error(__METHOD__ . ' detail 更新图书管理数据失败 data: ' . json_encode($data));
                throw new BooksException([
                    'msg' => '更新图书管理数据失败，请稍后再试～'
                ]);
            }

            $booksModel->commit();
        } catch (\Exception $e){
            $booksModel->rollback();
            throw $e;
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