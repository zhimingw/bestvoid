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
    //显示影片
    public function index(){
        $film = FilmHaveLabel::get_film_label();
        // var_dump($film);
        // die();
        View::assign('film',$film);
        return View::fetch();
    }
    //添加影片
    public function add(){
        return View::fetch();
    }
    //影片封面图片上传
    public function upload_img(){
        if($_FILES==null){
            exit(json_encode(array('code'=>1,'msg'=>'没有文件上传')));
        }
        if(!is_dir("uploads")){
            mkdir('uploads');
        }
        $path = "uploads/".$_FILES["file"]["name"];
        move_uploaded_file($_FILES["file"]["tmp_name"],$path);
        $ext = ($_FILES["file"]["type"]);
		if(!in_array($ext,array('image/jpg','image/jpeg','image/gif','image/png'))){
			exit(json_encode(array('code'=>1,'msg'=>'文件格式不支持')));
        }
        // $img = '/uploads/'.$path->getSaveName();
        // var_dump($img);
        // die();
        exit(json_encode(array('code'=>0,'msg'=>$path)));
    }
}