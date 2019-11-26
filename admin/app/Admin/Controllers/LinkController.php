<?php

namespace App\Admin\Controllers;
use App\Model\Link;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Model\Book;
use App\Model\BookCatalog;

class LinkController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\Link';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Link);

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('link', __('Link'));
        $grid->column('alt', __('Alt'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Link::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('link', __('Link'));
        $show->field('alt', __('Alt'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Link);

        $form->text('name', __('Name'));
        $form->url('link', __('Link'));
        $form->text('alt', __('Alt'));
        $form->switch('status', __('Status'));

        return $form;
    }

    public function submitBaidu(){
            $app_url = env('APP_URL');
            $api = ENV('BAIDU_API','');
            if(!$api){
                echo 'Please add BAIDU_API in .env,more info:<a href="http://www.sousuoyinqingtijiao.com/baidu/tijiao/">click here</a>';
            }
            $urls = [$app_url];
            $rs = Book::where('status',1)->select('id')->get();
            foreach($rs as $b){
                $urls[]=$app_url.'/book_'.$b->id;
            }
            
            $this->_submitToBaidu($urls);
    }

    public function submitBaiduToday(){
        echo date('Y-m-d H:i:s').":\n";
        $app_url = env('APP_URL');
        $api = ENV('BAIDU_API','');
        if(!$api){
            echo 'Please add BAIDU_API in .env,more info:<a href="http://www.sousuoyinqingtijiao.com/baidu/tijiao/">click here</a>';
        }
        $urls = [$app_url];
        $rs = Book::where('status',1)->where('created_at','>',date('Y-m-d H:i:s',strtotime('-24hours')))->select('id')->get();
        foreach($rs as $b){
            $urls[]=$app_url.'/book_'.$b->id;
        }
        $cats = BookCatalog::where('spider_status',1)->where('created_at','>',date('Y-m-d',strtotime('-24hours')))->select('id','book_id')->limit(6000)->get();
        foreach($cats as $b){
            $urls[]=$app_url.'/book_'.$b->book_id.'/'.$b->id.'.html';
        }
        
       $this->_submitToBaidu($urls);
    }

    private function _submitToBaidu($urls){
        $api = ENV('BAIDU_API');
        if(!$api){
            return ;
        }
        $urls_new = array_chunk($urls,1000);
        foreach($urls_new as $urls_chunk){
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $api,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => implode("\n", $urls_chunk),
                CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
            );
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            echo $result."\n";
        }
    }

    public function seoUpdate(){
       
        $books = Book::where('spider_status',1)->where('seo_update',0)->orderBy('created_at','desc')->limit(5)->get();
        foreach($books as $book){
            $result = $this->trans($book->des,'en');
            if(!$result){
                continue;
            }
            $tran =  $this->trans($result,'zh-cn');
            if(!$tran){
                continue;
            }
            echo date('Y-m-d H:i:s'),'#'.$book->id,' ',$book->name,"\n-------------------\n";
            echo $book->des ."\n--- to --- \n";
            echo $tran."\n";
            echo "---------------\n";
            $book->des = $tran;
            $book->seo_update = 1;
            $book->save();
        }
        
    }

    private function trans($text,$target_lg){
        $google_api = 'http://translate.google.cn/translate_a/single?client=gtx&dt=t&dj=1&ie=UTF-8&sl=auto&tl='.$target_lg.'&q='.\urlencode($text);
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $google_api,
            CURLOPT_SSL_VERIFYPEER=>FALSE,
            CURLOPT_SSL_VERIFYHOST=>false,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_HTTPHEADER => array(
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1',
            ),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
       // dump($result);
        curl_close($ch);
        if($result){
            try{
                $json = \json_decode($result,1);
            }catch(\Exception $e){
                echo trim(strip_tags($result))."\n";
                return false;
            }
            
        }
        if(!isset($json['sentences'])){
            
            return false;
        }
        //dump($json);
        $text = '';
        if(!is_array($json['sentences'])){
            return false;
        }
        foreach($json['sentences'] as $t){
            $text .= $t['trans'];
        }
        
        return $text;
    }

}
