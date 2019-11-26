<?php
namespace App\Front\Controllers;

//use Illuminate\Http\Request;
use App\Model\Book,
    App\Model\BookGood,
    App\Model\BookCatalog,
    App\Model\BookClass,
    Illuminate\Support\Facades\Cookie,
    DB,
    Response;
class HomeController extends BaseController
{
    function index(){
        $this->lastRead();
        $this->goodBook();
        $this->topBook();
        $this->lastUpdate();
        $this->banner();
        $this->assign('show_title',true);
        //$this->bookClass();
        return $this->view('home');
    }

    private function lastRead(){
        $read = Cookie::get('read');
        if($read){
            $read =  json_decode($read,1);
        }else{
            $read=[];
        }
        $last_read=null;
        if(isset($read['book'])){
            $book_ids = array_keys($read['book']);
            foreach($book_ids as $i=>$book_id){
                $book_ids[$i] = intval($book_id);//防止利用cookies注入
            }
            
            $books = Book::whereIn('id',$book_ids)->select('img','name','id')->limit(20)->get();
            if($books){
                $book_ids = array_reverse($book_ids);
                //让显示的顺序跟随cookies的倒序
                $books = $books->keyBy('id')->toArray();
                $last_read = [];
                foreach($book_ids as $id){
                    if(isset($books[$id])){
                        $last_read[] = $books[$id];
                    }
                }
            }
        }
        $this->assign('last_read',$last_read);
    }

    private function goodBook($num=25){
        //为了防止性能问题 不使用orderby rand();
        $min =  BookGood::min('id');
        $max =  BookGood::max('id');
        if(!$min or !$max){
            $this->assign('good_book',null);
            return;
        }
        $rand = rand($min,$max);
        $goodBook = BookGood::where('id','>=',$rand)->with('book')->limit($num)->get()->toArray();
        $count = count($goodBook);
        if($count<$num){
            $goodBook2 = BookGood::where('id','<',$rand)->with('book')->limit($num-$count)->get()->toArray();
            $goodBook = array_merge($goodBook,$goodBook2);
        }
        $this->assign('good_book',$goodBook);
    }

    private function topBook($num=20){
        $topBook = Book::where('spider_status',1)->orderBy('hot','desc')->limit($num)->get();
        $this->assign('topBook',$topBook);
    }

    private function lastUpdate($num=30){
        $ids = BookCatalog::where('spider_status',1)
        ->select(DB::raw('max(`id`) as m'))
        ->groupBy('book_id')->orderBy('created_at','desc')
        ->limit($num)
        ->pluck('m');
        $lastUpdate = BookCatalog::whereIn('id',$ids)->with('book')->get();
        $this->assign('lastUpdate',$lastUpdate);
    }

    private function bookClass($num=12){
        $rs = BookClass::orderBy('book_num','desc')->limit($num)->pluck('class');
        $this->assign('classes',$rs);
    }

    private function banner($num=1){
        $book = new \App\Front\Controllers\BookController;
        $this->assign('banner',$book->banner($num)); 
    }

}
