<?php

namespace App\Admin\Controllers;

use App\Model\Ad;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AxdController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\Ad';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Ad);
        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        $grid->filter(function($filter){
            $filter->like('target_url', '目标地址');
            $filter->equal('status','状态')->radio([
                ''   => '全部',0=>'停止',1=>'活跃'
            ]);
            $filter->equal('type','类型')->select([''=>'全部','banner' => 'banner', 'text' => '文字','code'=>'代码']);
        });
        $grid->column('id', __('Id'));
        $grid->column('type', __('Type'));
        $grid->column('target_url', __('Target url'));
        $grid->column('img', __('Img'));
        $grid->column('code', __('Code'));
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
        $show = new Show(Ad::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'));
        $show->field('target_url', __('Target url'));
        $show->field('img', __('Img'));
        $show->field('code', __('Code'));
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
        $form = new Form(new Ad);

        $form->select('type', __('类型'))->options(['banner' => 'banner', 'text' => '文字','code'=>'代码'])->default(0);
        $form->url('target_url', __('Target url'));
        $form->image('img', __('Img'))->help('仅仅为banner时候生效');
        $form->text('code', __('Code'))->help('仅仅为code时候生效');
        $states = [
            'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
        ];
        $form->switch('status', __('Status'))->states($states);;

        return $form;
    }
}
