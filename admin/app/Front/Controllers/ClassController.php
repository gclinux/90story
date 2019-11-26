<?php
namespace App\Front\Controllers;
use App\Model\BookClass,
    Illuminate\Http\Request,
    App\Model\Book;

class ClassController extends BaseController
{
    function index(){
        $rs = BookClass::orderBy('book_num','desc')->select('img','book_num','class')->get();
        $this->assign('classes',$rs);
        return $this->view('class');
    }

    private $limit =5;
    function show($keyword,Request $req){
        $this->assign('limit',$this->limit);
        $this->assign('keyword2',$keyword);
        $books = Book::where('class',$keyword)->orderBy('id','DESC')->limit($this->limit)->get();
        if($books){
            $books = $books->toArray();
        }else{
            $history = new SearchHistory();
            $history->keyword = $keyword; //目前仅保存没有找到书本的关键词
            $history->save();
            $books = [];
        }
        $this->assign('count',count($books));
        $lastbook = end($books);
        $this->assign('books',$books);
        $this->assign('last_id',$lastbook['id']);
        return $this->view('classbook');
    }
    function ajax($keyword,$last_id){
        $last_id = intval($last_id);
        $books = Book::where('id','<',$last_id)->where('class',$keyword)->orderBy('id','DESC')->limit($this->limit)->get();
        if(!$books){
            return null;
        }else{
            $books = $books->toArray();
        }
        $lastbook = end($books);
        return ['books'=>$books,'last_id'=>$lastbook['id']];
    }

}
