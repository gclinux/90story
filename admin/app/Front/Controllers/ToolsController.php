<?php
namespace App\Front\Controllers;
use Illuminate\Http\Request;
use App\Model\BookCatalog;
class ToolsController {
    function css(){

    }

    function js(){
        
    }

    function server(){
        echo request()->ip();
    }

    function deleteLuoxiaLink(){
        set_time_limit(0);
        $page = 0;
        do{
            $cats = BookCatalog::where('file','luoxia.com.js')->with('content')->skip($page*500)->take(500)->get();
            foreach($cats as $cat){
                $newContent=$this->strip_html_tags(['a'],$cat->content->content);
                $cat->content->content = $newContent;
                $cat->content->save();
                echo $cat->book_id,' ',$cat->id,'->',$cat->content->id,"<br>\n";
                $count = $cats->count();
                
            }
            $page++;
            echo ">>>> $page >>>> ";
        }while($count==500);
    }

    function strip_html_tags($tags,$str){
        $html=array();
        foreach ($tags as $tag) {
            $html[]='/<'.$tag.'.*?>[\s|\S]*?<\/'.$tag.'>/';
            $html[]='/<'.$tag.'.*?>/';
        }
        $html[]='#<p>.{0,3}落.{0,10}霞.*?</p>#';
        $data=preg_replace($html,'',$str);
        return $data;
    }
}