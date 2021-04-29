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

    public static function getBorrowList($page)
    {
        $borrowModel = new BorrowModel();
        $list = $borrowModel->alias('b')
                            ->join('books bk', 'b.b_no = bk.b_no')
                            ->join('student st', 'b.s_no = st.st_id')
                            ->page($page)->field('bname,author,b.b_no,st.st_id,st_name
                             borrow_at,latest_at,return_at,fine,mark,borrow_status')->select();

        return $list;
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

    public static function search($key, $page)
    {
        $borrowModel = new BorrowModel();
        return $borrowModel->where('b_no', 'like', "%{$key}%")
            ->whereOr('s_no', 'like', "%$key%")
            ->page($page)
            ->select();
    }
}