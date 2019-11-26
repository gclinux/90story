<?php
namespace App\Front\Controllers;
use Validator,
    App\Model\Book, 
    App\Model\BookCatalog,
    App\Model\Ad,
    Illuminate\Support\Facades\Cookie,
    Response;
//use Illuminate\Http\Request;

class BookController extends BaseController
{
    
    private function lastRead($book_id){
        $read = Cookie::get('read');
        if($read){
            $read =  json_decode($read,1);
        }else{
            $read=[];
        }
        $last_read=null;
        if(isset($read['book'])){
            if(isset($read['book'][$book_id.''])){
                $last_read = BookCatalog::find(intval($read['book'][$book_id]));
            };
        }
        $this->assign('last_read',$last_read);
    }
    
    function index($book_id){
        Validator::make(['id'=>$book_id], [
            'id' => 'required|min:1|integer',
        ]);
        $book_id = intval($book_id);
        $book = Book::findOrFail($book_id);
        $this->assign('book',$book);
        $this->lastRead($book_id);
        $firstCat = BookCatalog::where('book_id',$book_id)->orderBy('book_id')->orderBy('inx')->orderBy('num')->with('content')->first();
        if($this->spider){
             $cats = BookCatalog::where('book_id',$book_id)->orderBy('book_id')->orderBy('inx','desc')->orderBy('num','desc')->limit(80)->get();
        }else{
            $cats = BookCatalog::where('book_id',$book_id)->orderBy('book_id')->orderBy('inx')->orderBy('num')->get();
        }
        $this->assign('firstCat',$firstCat);
        $this->assign('cats',$cats);
        //dump($firstCat->content->content);
        return $this->view('book');
    }

    function catalog($book_id,$cat_id){
        $this->lastRead($book_id);
        $cat = BookCatalog::select('name', 'inx','num','book_id','id')->with('content')->findOrFail($cat_id);

        if($this->spider){
             $cats = BookCatalog::where('book_id',$cat->book_id)->orderBy('book_id')->orderBy('inx','desc')->orderBy('num','desc')->limit(80)->get();
        }else{
            $cats = BookCatalog::where('book_id',$cat->book_id)->orderBy('book_id')->orderBy('inx')->orderBy('num')->get();
        }
        $this->assign('firstCat',$cat);
        $this->assign('cats',$cats);
        return $this->view('catalog');
    }

    function cataNext($book_id,$inx,$num){
        $this->lastRead($book_id);
        Validator::make(['book_id'=>$book_id,'inx'=>$inx,$num=>$num], [
            'book_id' => 'required|min:1|integer',
            'inx' => 'required|min:0|integer',
            'num' => 'required|num:0|integer',
        ]);
        
        $cat = BookCatalog::select('name', 'inx','num','book_id','id')
            ->where('book_id',$book_id)->where('inx',$inx)
            ->where('num','>',$num)->orderBy('book_id')
            ->orderBy('inx')->orderBy('num')
            ->with('content')->first();
        if(!$cat){
            $cat = BookCatalog::where('book_id',$book_id)->where('inx','>',$inx)->where('num',0)->orderBy('book_id')->orderBy('inx')->orderBy('num')->with('content')->first();
        }
        if(!$cat){
            return Response::make('<a href="/book_'.$book_id.'">已经没有更多的章节,您可以尝试点击返回小说首页</a>');
        }
        if($this->spider){
             $cats = BookCatalog::where('book_id',$cat->book_id)->orderBy('book_id')->orderBy('inx','desc')->orderBy('num','desc')->limit(80)->get();
        }else{
            $cats = BookCatalog::where('book_id',$cat->book_id)->orderBy('book_id')->orderBy('inx')->orderBy('num')->get();
        }
        $cat->book()->increment('hot');//每看一章加一个hot
        $this->assign('firstCat',$cat);
        $this->assign('cats',$cats);
        return $this->view('catalog');
    }

    function catBug($cat_id){
        $cat = BookCatalog::findOrFail($cat_id);
        if($cat->spider_status == 1){
            $cat->spider_status = 2;
            $cat->save();
        }
        return ['success'=>1];
    }

    public function banner($num=1){
        $default = [['target_url'=>'#','img'=>'images/banner.jpg']];
        //为了防止性能问题 不使用orderby rand();
        $min =  Ad::min('id');
        $max =  Ad::max('id');
        if(!$min or !$max){
          return $default;
        }
        $rand = rand($min,$max);
        $ad1 = Ad::where('id','>=',$rand)->where('type','banner')->where('status',1)->limit($num)->get()->toArray();
        $count = count($ad1);
        if($count<$num){
            $ad2 = Ad::where('id','<',$rand)->where('type','banner')->where('status',1)->limit($num-$count)->get()->toArray();
            $ad1 = array_merge($ad1,$ad2);
        }
        if(count($ad1)<1){
            return $default;
        }
        return $ad1;
    }

    function nextCat($book_id,$inx,$num){
        Validator::make(['book_id'=>$book_id,'inx'=>$inx,$num=>$num], [
            'book_id' => 'required|min:1|integer',
            'inx' => 'required|min:0|integer',
            'num' => 'required|num:0|integer',
        ]);
        $cat = BookCatalog::select('name', 'inx','num','book_id','id')
            ->where('book_id',$book_id)->where('inx',$inx)
            ->where('num','>',$num)->orderBy('book_id')
            ->orderBy('inx')->orderBy('num')
            ->with('content')->first();
        if(!$cat){
            $cat = BookCatalog::where('book_id',$book_id)->where('inx','>',$inx)->where('num',0)->orderBy('book_id')->orderBy('inx')->orderBy('num')->with('content')->first();
        }
        if(!$cat){
            return Response::make('null');
        }
        $cat->book()->increment('hot');//每看一章加一个hot
        $read = Cookie::get('read');
        if($read){
            $read =  json_decode($read,1);
        }
        if(!$read or !isset($read['book'])){
            $read=[];
            $read['book']=[];
        }
        $banner = $this->banner(1);
        unset($read['book'][$cat->book_id.'']);//删除是为了保证最后读过的会在数组最后面
        $read['book'][$cat->book_id.'']=$cat->id;
        $cookie=Cookie::make('read', json_encode($read));
        return Response::make(json_encode(['cat'=>$cat,'axd'=>$banner[0]]))->withCookie($cookie);
        return $cat;
    }

}
