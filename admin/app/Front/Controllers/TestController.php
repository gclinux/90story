<?php
namespace App\Front\Controllers;

//use Illuminate\Http\Request;

class TestController extends BaseController
{
    function index(){
       return $this->view('test');
    }

    function getUa(){
        echo $_SERVER['HTTP_USER_AGENT'];
    }
}
