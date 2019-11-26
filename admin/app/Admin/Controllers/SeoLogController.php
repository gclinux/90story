<?php

namespace App\Admin\Controllers;

use App\Model\SeoLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SeoLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\SeoLog';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SeoLog);

        $grid->column('id', __('Id'));
        $grid->column('spider', __('Spider'));
        $grid->column('url', __('Url'));
        $grid->column('ip', __('Ip'))->display(function($d){
            return long2ip($d);
        });
        $grid->column('ua', __('Ua'));
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
        $show = new Show(SeoLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('spider', __('Spider'));
        $show->field('url', __('Url'));
        $show->field('ip', __('Ip'));
        $show->field('ua', __('Ua'));
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
        $form = new Form(new SeoLog);

        $form->text('spider', __('Spider'));
        $form->url('url', __('Url'));
        $form->number('ip', __('Ip'));
        $form->text('ua', __('Ua'));

        return $form;
    }
}
