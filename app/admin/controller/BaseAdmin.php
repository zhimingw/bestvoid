<?php
/*
 * 让Home控制器继承
 */
namespace app\admin\controller;

use app\BaseController;

class BaseAdmin extends BaseController
{
    public function  __construct(){
//        parent::__construct();
        $admin = session('admin');
        // 未登录的用户不允许访问;
        if(!$admin){
            header('Location:/admin.php/Account/login/');
            exit;
        }
    }
}