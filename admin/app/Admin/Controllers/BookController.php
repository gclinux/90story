<?php

namespace App\Admin\Controllers;

use App\Model\Book;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Admin\Actions\Post\AddGood,
    App\Admin\Actions\Post\DeleteBook,
    App\Model\BookClass,
    DB;
class BookController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '书库管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Book);
        $grid->actions(function ($actions) {
                $actions->add(new AddGood);
                $actions->add(new DeleteBook);
                $actions->disableView();
                $actions->disableDelete();
        });
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });
        $grid->filter(function($filter){
            $filter->like('name', '书名');
            $filter->like('author', '作者');
            $filter->like('class', '分类');
            $filter->equal('status','状态')->radio([
                ''   => '全部',0=>'连载中',1=>'完结'
            ]);

        });
        $grid->column('id', __('Id'));
        $grid->column('img', __('封面'))->lightbox(['height'=>90]);
        $grid->column('name', __('书名'))->display(function($name){
            return '<a href="/admin/catalogs?book_id='.$this->id.'">'.$name.'</a>';
        });
        $grid->column('author', __('作者'))->display(function($author){
            return '<a href="/admin/books?author='.$author.'">'.$author.'</a>';
        });
        $grid->column('des', __('简介'))->display(function ($str) {
                return '<p class="text-justify" style="max-width:480px">'.mb_substr($str, 0, 100, 'utf-8').'</p>' ;
        });
        $grid->column('status', __('状态'))->display(function($bool){
            return $bool==0?'连载':'完结';
        });
        $grid->column('class', __('分类'));
        $grid->column('hot', __('热度'));
        $grid->column('file', __('处理文件'));
        $grid->column('created_at', __('入库时间'));
        $grid->column('updated_at', __('最后更新'));

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
        //$show = new Show(Book::findOrFail($id));
        //return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Book);
        
        $form->text('name', __('书名'))->required();
        $form->text('author', __('作者'))->required();
        $form->text('class', __('分类'))->required()->default('未分类');
        $form->textarea('des', __('介绍'))->default('');
        $form->text('url', __('转载于'))->help('请填写资料节的页面地址')->required();
        $form->text('cat_url', __('章节地址'))->help('请填写带章节的页面地址,如果为空,表示用上面地址')->default('');
        $form->text('file', __('处理文件'))->help('请填写爬虫处理文件');
        $form->text('img', __('图片来源'));
        $form->radio('img_down', __('图片类型'))->default(0)
            ->options(['0'=>'网络盗链',1=>'本地图片']);
        //$form->text('book_sign','书本的哈希码')->required()->help('请不要修改,用于爬虫识别书本的唯一性,修改了可能会导致书本重复');
        $form->radio('status', __('更新状态'))->default(0)->required()
            ->options([0=>'连载',1=>'完结']);
        $form->radio('spider_status', __('爬虫状态'))->default(0)->required()
            ->options([0=>'书本信息不全',1=>'书本信息完整']);
        $form->radio('seo_update', __('伪原创状态'))->default(0)->required()
            ->options([0=>'未进行转换',1=>'已经转换']);
        $form->number('hot', __('Hot'))->default(0)->help('hot值越高,排的越前');
        $form->number('retry', __('抓取重试'))->default(0)->help('抓取重试次数');
        return $form;
    }

    public function getBooks(Request $request)
    {
        $q = $request->get('q');
        return Book::where('name', 'like', "%$q%")->paginate(null, ['id', 'name AS text']);
    }

    public function syncClass(){
        echo date('Y-m-d H:i:s')."\n";
        $rs = Book::select(DB::raw('count(*) as num,class,img'))->groupBy('class')->get();
        if(!$rs){
            echo 'no books';
            return;
        }
        $bookClass = new BookClass;
        $sql = 'insert into `'.config('database.connections.mysql.prefix'). $bookClass->getTable().'` (`class`,`book_num`,`img`)values';
        foreach($rs as $k=>&$row){
            $values[] = "('{$row->class}',$row->num,'$row->img')";
        }
        $sql = $sql.implode(',',$values).' ON DUPLICATE KEY UPDATE `book_num`=VALUES(`book_num`),`img`=VALUES(`img`)';
        $r = DB::insert($sql);
        echo '执行完毕';
        dump($r);
    }

}
