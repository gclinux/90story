<?php
namespace App\Front\Controllers;

use Illuminate\Http\Request,
    App\Model\BookGood;


class IntroductionsController extends BaseController
{
    private $limit =5;
    function show(Request $req){
        $this->assign('limit',$this->limit);
        $page = intval($req->input('page'));
        if($page<1){
            $page = 1;
        }
        $skip =($page-1)*$this->limit;
        $books = BookGood::orderBy('hot','DESC')->skip($skip)->take($this->limit)->with('book')->get();
        if($books){
            $books = $books->toArray();
        }else{
            $books = [];
        }
        $this->assign('count',count($books));
        $this->assign('books',$books);
        $this->assign('last_id',++$page);
        return $this->view('introductions');
    }
    function ajax($page){
        $page = intval($page);
        if($page<1){
            $page = 1;
        }
        $skip = ($page-1)*$this->limit;
        $books = BookGood::orderBy('hot','DESC')->skip($skip)->take($this->limit)->with('book')->get();
        if(!$books){
            return null;
        }else{
            $books = $books->toArray();
        }
        return ['books'=>$books,'last_id'=>++$page];
    }
}
