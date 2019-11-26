<?php
namespace App\Front\Controllers;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Link;
use App\Model\SeoLog;
class BaseController extends Controller
{
    protected $view_assign=[];
    protected $is_phone = false;
    protected $spider ;
    function __construct(){
        
        $this->assign('static_ver',0.99);
        $this->is_phone = $this->is_mobile_request();
        $this->assign('show_title',true);
        $this->assign('is_iphone',($this->isIphone()));
        $this->spider = $this->isSpider();
        if($this->spider){
            $log = new SeoLog();
            $log->spider = $this->spider;
            $log->ua = @$_SERVER['HTTP_USER_AGENT'];
            $log->ip = \ip2long(request()->ip());
            $log->url = @$_SERVER['HTTP_HOST'].@$_SERVER['REQUEST_URI'];
            $log->save();
        }
        $this->assign('spider',$this->spider);
        $links = Link::where('status',1)->get();
        $this->assign('links',$links);
    }
    private function is_app(){   
        $ag = @$_SERVER['HTTP_USER_AGENT'];
        strpos($ag,"Html5Plus");
    }
    private function isIphone(){
        $_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
        $mobile_ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        if(strpos($mobile_ua,"ios")||strpos($mobile_ua,"iphone")||strpos($mobile_ua,"ipad")){
            return 1;
        }else{
            return 0;
        };
    }
    private function is_mobile_request(){  
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
        $_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
        $mobile_browser = '0';  
        if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom|baidu|and|mobile)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
            $mobile_browser++;  
        if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
            $mobile_browser++;  
        if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
            $mobile_browser++;  
        if(isset($_SERVER['HTTP_PROFILE']))  
            $mobile_browser++;  
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
        $mobile_agents = array(  
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
            'wapr','webc','winw','winw','xda','xda-'
            );  
        if(in_array($mobile_ua, $mobile_agents))  
            $mobile_browser++;  
        if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
            $mobile_browser++;  
        // Pre-final check to reset everything if the user is on Windows  
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
            $mobile_browser=0;  
        // But WP7 is also Windows, with a slightly different characteristic  
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
            $mobile_browser++;  
        if($mobile_browser>0)  
            return true;
        else
            return false;  
    }
    protected function assign($key,$value){
        $this->view_assign[$key] = $value;
    }
    protected function view($file,$data=[]){
        $data = array_merge($this->view_assign,$data);
        if($this->is_phone){
            return view('phone/'.$file,$data);
        }else{
            return view('pc/'.$file,$data);
        }
    }
    protected function isSpider(){
        $userAgent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:false;
        if(!$userAgent){
            return false;
        }
        $userAgent = strtolower($userAgent);
        $spiders = [
            'Googlebot', // Google 爬虫
            'Baiduspider', // 百度爬虫
            'Yahoo! Slurp', // 雅虎爬虫
            'YodaoBot', // 有道爬虫
            'msnbot',
            '360spider',
            'AdsBot','bingbot','Sosospider','Sosoimage','YandexBot','EasouSpider',
            
        ];
        foreach($spiders as $spider)
        {
            $spider = strtolower($spider);
            //查找有没有出现过
            if(strpos($userAgent, $spider) !== false){
                return $spider;
            }
        }
    }
}
