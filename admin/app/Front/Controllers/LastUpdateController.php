<?php
namespace App\Front\Controllers;

use Illuminate\Http\Request,
    App\Model\Book;


class LastUpdateController extends BaseController
{
    private $limit =10;
    function show(Request $req){
        $this->assign('limit',$this->limit);
        $page = intval($req->input('page'));
        if($page<1){
            $page = 1;
        }
        if($page>20){
            return false;
        }
        $skip = ($page-1)*$this->limit;
        $books = Book::orderBy('updated_at','desc')->skip($skip)->take($this->limit)->get();
        if($books){
            $books = $books->toArray();
        }else{
            $books = [];
        }
        $this->assign('count',count($books));
        $this->assign('books',$books);
        $this->assign('last_id',++$page);
        return $this->view('lastupdate');
    }
    function ajax($page){
        $page = intval($page);
        if($page<1){
            $page = 1;
        }
        if($page>10){
            return 'null';
        }
        $skip = ($page-1)*$this->limit;
        $books = Book::orderBy('updated_at','desc')->skip($skip)->take($this->limit)->get();
        if(!$books){
            return null;
        }else{
            $books = $books->toArray();
        }
        return ['books'=>$books,'last_id'=>++$page];
    }
}
