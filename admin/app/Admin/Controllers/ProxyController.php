<?php

namespace App\Admin\Controllers;

use App\Model\Proxy as Proxy;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Post\Spider;
class ProxyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\Proxy';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Proxy);
        $grid->disableBatchActions();
        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new Spider());
        });
        $grid->actions(function ($actions) {
            //$actions->disableDelete();
            $actions->disableView();
            //$actions->add(new RedisDelete);
        });
        $grid->column('id', __('Id'));
        $grid->column('ip', __('IP'));
        $grid->column('port', __('Port'));
        $grid->column('type', __('类型'));
        $grid->column('anony', __('匿名'));
        $grid->column('from', __('来源'));
        $grid->column('addr', __('所在地'));
        $grid->column('ping', __('延时'));
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
        $show = new Show(Proxy::findOrFailStatic($id));
        $show->field('ip', __('IP'));
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Proxy);
        $form->ip('ip', __('IP'));
        $form->number('port', __('Port'));
        $form->select('type', __('类型'))->options(['http' => 'http', 'https' => 'https'])->default('http');
        $form->select('anony ', __('匿名'))->default('透明')->options(['匿名' => '匿名', '透明' => '透明']);
        $form->text('addr', __('所在地'))->default('CN');
        $form->number('ping', __('延时'));
        return $form;
    }

    public function spiderIp($max_page=3){
        set_time_limit(0);
        ignore_user_abort();
        $spider = new \App\Service\IpSpiderService();
        $spider->spiderIp($max_page);
    }

}
