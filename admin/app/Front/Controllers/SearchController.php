<?php
namespace App\Front\Controllers;

use Illuminate\Http\Request,
    App\Model\Book,
    App\Model\SearchHistory;

class SearchController extends BaseController
{
    private $limit =5;
    function show(Request $req){
        $this->assign('limit',$this->limit);
        $keyword = $req->input('keyword');
        if($keyword == 'joffe很帅'){
            return '<a href="/test" class="external">测试页面</a>';

        }
        $this->assign('keyword',$keyword);
        $books = Book::where('name','like','%'.$keyword.'%')->orderBy('id','ASC')->limit($this->limit)->get();
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
        return $this->view('search');
    }
    function ajax($keyword,$last_id){
        $last_id = intval($last_id);
        $books = Book::where('id','>',$last_id)->where('name','like','%'.$keyword.'%')->orderBy('id','ASC')->limit($this->limit)->get();
        if(!$books){
            return null;
        }else{
            $books = $books->toArray();
        }
        $lastbook = end($books);
        return ['books'=>$books,'last_id'=>$lastbook['id']];
    }
}
