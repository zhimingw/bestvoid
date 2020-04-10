<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\View;
use app\admin\controller\baseAdmin;
use app\admin\model\Film;
use app\admin\model\Label;
use app\admin\model\FilmHaveLabel;
/*影片管理*/
class FilmManage extends baseAdmin
{
    public function index(){
        $film = FilmHaveLabel::get_film_label();
        var_dump($film);
        die();
        return View::fetch();
    }
}