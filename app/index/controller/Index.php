<?php
namespace app\index\controller;
use think\facade\View;
use app\BaseController;
use think\facade\Db;

class Index extends BaseController
{
    public function index()
    {
        return View::fetch();
    }
}
