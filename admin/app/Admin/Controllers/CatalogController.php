<?php

namespace App\Admin\Controllers;

use App\Model\BookCatalog;
use App\Model\Book;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CatalogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '章节管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BookCatalog);
        $grid->filter(function($filter){
            $filter->like('name', '章节名');
            $filter->equal('book_id', '书ID');
            $filter->equal('spider_status','爬取状态')->radio([
                ''   => '全部',0=>'未爬取',1=>'已爬取',2=>'有错误'
            ]);
        });
        $grid->model()->orderBy('book_id', 'desc')->orderBy('inx', 'desc')->orderBy('num', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('inx', __('顺序1'));
        $grid->column('num', __('顺序2'));
        $grid->column('name', __('章节名'))->display(function($name){
            return '<a href="/admin/catalogs/'.$this->id.'/edit">'.$name.'</a>';
         });
        $grid->column('book', __('书名'))->display(function($book_id){
           return '<a href="/admin/books?id='.$this->book_id.'">'.$this->book->name.'</a>';
        });
        $grid->column('author', __('作者'))->display(function($book_id){
            return $this->book->author;
         });

        $grid->column('spider_status', __('爬取状态'))
            ->using([0=>'未爬取',1=>'已爬取',2=>'有错误'],'未知')
            ->dot([0=>'warning',1=>'success',2=>'danger'],'danger');
        //$grid->column('type', __('Type'));
        $grid->column('open_type', __('Open type'))->using(['0' => '禁止访问', '1' => '免费访问' , '2' => '付费访问']);
       // $grid->column('url', __('Url'))->link();
        $grid->column('file', __('处理文件'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

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
        $show = new Show(BookCatalog::findOrFail($id));
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new BookCatalog);
        $form->column(7/12, function ($form) {
            $form->select('book_id','关联的书本')->options(function ($id) {
                $book = Book::find($id);
                if ($book) {
                    return [$book->id => $book->name];
                }
            })->ajax('/admin/api/books/getBooks');
            $form->text('name', __('章节标题'));
            $form->editor('content.content',__('内容'));
            $form->radio('type', __('章节类型'))->default(1)
            ->options(['1'=>'带章节顺序,如 第一章 魔王的诞生','2'=>'不带章节顺序,例如 魔王的诞生']);
        });
        $form->column(5/12,function($form){
            $form->url('url', __('转载于'))->help('转载的地址,添加后如果抓取状态为未抓取就会去抓取')->default('');
            $form->radio('spider_status', __('抓取状态'))->default(0)
                ->options([0=>'未抓取内容',1=>'已经抓取内容',2=>'抓取错误']);
            $form->number('inx', __('章节索引'))->help('请不要修改');
            $form->number('num', __('插入索引'))->help('用于插入章节,请不要修改')->default(0);
            $form->radio('open_type', __('开放程度'))->options([0=>'禁止访问,建议内容没有时候设置',1=>'免费阅读',2=>'收费阅读']);
            $form->text('file', __('处理文件'))->help('非技术人员请勿修改');
        });
        return $form;
    }
}
