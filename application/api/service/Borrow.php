<?php


namespace app\api\service;
use app\api\model\Borrow as BorrowModel;
use app\api\model\Detail as DetailModel;
use app\api\model\Student;
use app\exception\BooksException;
use app\exception\BorrowException;
use app\api\model\Books as BooksModel;
use app\exception\StudentException;
use think\Log;

class Borrow
{
    const DAY_SECOND = 86400;
    const BORROW_SUCCESS = 1;
    public static function getBorrowList($page, $status)
    {
        $res = [
            'list' => [],
            'count' => 0,
            'page' => 1,
        ];
        $borrowModel = new BorrowModel();
        $conditions = [];
        if ($status != 2){
            $conditions['borrow_status'] = $status;
        }
        $count = $borrowModel->alias('b')
            ->join('books bk', 'b.b_no = bk.b_no')
            ->join('student st', 'b.s_no = st.st_id')
            ->where($conditions)->count();
        if ($count == 0){
            return $res;
        }

        $list = $borrowModel->alias('b')
                            ->join('books bk', 'b.b_no = bk.b_no')
                            ->join('student st', 'b.s_no = st.st_id')
                            ->page($page)->field('b.id,bname,author,b.b_no,st.st_id,st_name,
                             borrow_at,latest_at,return_at,fine,mark,borrow_status')->limit(7)->where($conditions)->select();

        $res = [
            'list' => $list,
            'count' => (int)$count,
            'page' => (int)$page
        ];
        return $res;
    }


    public static function leadBook($b_no, $st_id)
    {
        $detailModel = new DetailModel();
        $booksModel = new BooksModel();
        $studentModel = new Student();
        $info = $detailModel->where(['b_no' => $b_no])->field('status')->find();
        if (!$info->getData('status')){
            throw new BorrowException([
                'msg' => '该书不可借出～'
            ]);
        }

        $booksInfo = $booksModel->where(['b_no' => $b_no])->find();
        if($booksInfo->getData('now_stock') == 0){
            $info->status = 0;
            $info->save();
            throw new BorrowException([
                'msg' => '该书没有库存了'
            ]);
        }

        $studentInfo = $studentModel->where(['st_id' => $st_id])->find();
        if (!$studentInfo){
            throw new StudentException([
                'msg' => '该学生不存在或者已删除～'
            ]);
        }

        $borrowModel = new BorrowModel();
        $borrowInfo = $borrowModel->getOne([
            'b_no' => $b_no,
            's_no' => $st_id,
            'borrow_status' => 0
        ]);

        if ($borrowInfo){
            throw new BorrowException([
                'msg' => '你已经借过这本书了',
            ]);
        }

        $data = [
            'b_no' => $b_no,
            's_no' => $st_id,
            'borrow_at' => date('Y-m-d H:i:s'),
            'latest_at' => date('Y-m-d H:i:s', strtotime('+1 month')),
        ];
        $borrowModel->startTrans();
        try {
            $res = $booksInfo->setDec('now_stock');
            if (!$res){
                Log::error(__METHOD__ . ' 库存减少失败b_no:' . $b_no . ' s_no:' . $st_id);
                $borrowModel->rollback();
                throw new BooksException([
                    'msg' => '库存减少失败'
                ]);
            }

            $borrowRes = $borrowModel->insert($data);
            if (!$borrowRes){
                Log::error(__METHOD__ . ' borrow表插入失败 b_no:' . $b_no . ' s_no:' . $st_id);
                $borrowModel->rollback();
                throw new BorrowException([
                    'msg' => '借书失败，请稍后再试'
                ]);
            }

            $borrowModel->commit();
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }

    public static function returnBook($id)
    {
        $detailModel = new DetailModel();
        $booksModel = new BooksModel();
        $borrowModel = new BorrowModel();

        $borrowInfo = $borrowModel->getOne([
            'id' => $id,
        ]);

        if ($borrowInfo->getData('borrow_status') == self::BORROW_SUCCESS){
            throw new BorrowException([
                'msg' => '你已经归还这本书了',
            ]);
        }
        $borrowModel->startTrans();
        try {
            $info = $detailModel->where(['b_no' => $borrowInfo->getData('b_no')])->find();
            $booksInfo = $booksModel->where(['b_no' => $borrowInfo->getData('b_no')])->find();
            if($info->status == 0){
                $info->status = 1;
            }

            $res = $booksInfo->setInc('now_stock');
            if (!$res){
                Log::error(__METHOD__ . ' 库存增加失败b_no:' . $borrowInfo->getData('b_no') . ' s_no:' . $borrowInfo->getData('s_no'));
                $borrowModel->rollback();
                throw new BooksException([
                    'msg' => '库存增加失败，请稍后再试'
                ]);
            }


            $updateData = [
                'return_at' => date('Y-m-d H:i:s'),
                'fine' => self::_getFine($id),
                'borrow_status' => self::BORROW_SUCCESS, // 还书成功
            ];
            $borrowRes = $borrowModel->save($updateData, [
                'id' => $id,
            ]);

            if (!$borrowRes){
                Log::error(__METHOD__ . ' borrow表更新失败 b_no:' . $borrowInfo->getData('b_no') . ' s_no:' . $borrowInfo->getData('s_no'));
                $borrowModel->rollback();
                throw new BorrowException([
                    'msg' => '还书失败，请稍后再试'
                ]);
            }

            $borrowModel->commit();
        } catch (\Exception $e) {
            $borrowModel->rollback();
            throw $e;
        }

        return true;
    }

    public static function search($key, $page)
    {
        $res = [
            'list' => [],
            'count' => 0,
            'page' => 1,
        ];
        $borrowModel = new BorrowModel();
        $count = $borrowModel->where('b_no', 'like', "%{$key}%")
            ->whereOr('s_no', 'like', "%$key%")
            ->count();
        if ($count == 0){
            return $res;
        }

        $list = $borrowModel->alias('b')
                ->join('books bk', 'b.b_no = bk.b_no')
                ->join('student st', 'b.s_no = st.st_id')
                ->page($page)->limit(5)->field('bname,author,b.b_no,st.st_id,st_name,
                             borrow_at,latest_at,return_at,fine,mark,borrow_status')
            ->where('b.b_no', 'like', "%{$key}%")
            ->whereOr('b.s_no', 'like', "%$key%")
            ->select();
        $res = [
            'list' => $list,
            'count' => (int)$count,
            'page' => (int)$page
        ];
        return $res;
    }

    public static function _getFine($id){
        $borrowModel = new BorrowModel();
        $conditions = [
            'id' => $id,
        ];
        $info = $borrowModel->getOne($conditions);
        $latestAt = $info->getData('latest_at');

        if (date('Y-m-d H:i:s') < $latestAt) {
            return 0;
        }

        $diffSecond = time() - strtotime($latestAt);
        return round($diffSecond / self::DAY_SECOND, 2) * 100;
    }
}