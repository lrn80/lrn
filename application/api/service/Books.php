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
        $res = [
            'list' => [],
            'count' => 0,
            'page' => 1
        ];
        $booksModel = new BooksModel();
        $count = $booksModel->count();
        if ($count == 0){
            return $res;
        }

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
        $res = [
            'list' => $booksList,
            'count' => (int)$count,
            'page' => (int)$page
        ];
        return $res;
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
                Log::error(__METHOD__ . ' ?????????????????? data:' . json_encode($data));
                throw new BooksException([
                    'msg' => "?????????????????????"
                ]);
            }

            $detailRes = $detailModel->insert($detail_data);
            if (!$detailRes){
                Log::error(__METHOD__ . '  detail ?????????????????? data:' . json_encode($data));
                throw new BooksException([
                    'msg' => "?????????????????????"
                ]);
            }

            $booksModel->commit();
        } catch (\Exception $e){
            $booksModel->rollback();
            throw new BooksException([
                'msg' => '???????????????????????????????????????'
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
                'msg' => '?????????????????????????????????????????????'
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
                Log::error(__METHOD__ . ' ?????????????????????????????? data: ' . json_encode($data));
                throw new BooksException([
                    'msg' => '???????????????????????????????????????????????????'
                ]);
            }

            $detailRes = $detailModel->update($detailData, ['b_no' => $info['b_no']]);
            if (!$detailRes){
                $booksModel->rollback();
                Log::error(__METHOD__ . ' detail ?????????????????????????????? data: ' . json_encode($data));
                throw new BooksException([
                    'msg' => '???????????????????????????????????????????????????'
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
        $res = [
            'list' => [],
            'count' => 0,
            'page' => 1
        ];
        $booksModel = new BooksModel();
        $count = $booksModel->where('bname', 'like', "%{$key}%")->whereOr('author', 'like', "%$key%")->count();
        if ($count == 0){
            return $res;
        }
        $list = $booksModel->where('bname', 'like', "%{$key}%")
                   ->whereOr('author', 'like', "%$key%")
                   ->page($page)
                   ->select();
        $res = [
            'list' => $list,
            'count' => (int)$count,
            'page' => (int)$page
        ];
        return $res;
    }
}