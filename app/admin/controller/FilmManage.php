<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\View;
use app\admin\controller\baseAdmin;
use app\admin\model\Film;
use app\admin\model\Label;
use app\admin\model\FilmHaveLabel;
use think\facade\Lang;

/*影片管理*/
class FilmManage extends baseAdmin
{
    //显示影片
    public function index(){
        $film = FilmHaveLabel::get_film_label();
        $page = Film::get_page();
        // var_dump($film);
        // die();
        View::assign([
            'page'=>$page,
            'film'=>$film
            ]);
        return View::fetch();
    }
    //添加影片
    public function add(){
        $data['channel'] = Label::get_channel();
        $data['charge'] = Label::get_charge();
        $data['area'] = Label::get_area();
        // var_dump($data);
        // die();
        View::assign('data',$data);
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
        // var_dump($path);
        move_uploaded_file($_FILES["file"]["tmp_name"],$path);
        $ext = ($_FILES["file"]["type"]);
		if(!in_array($ext,array('image/jpg','image/jpeg','image/gif','image/png'))){
			exit(json_encode(array('code'=>1,'msg'=>'文件格式不支持')));
        }
        $img = 'http://www.bestvoid.com/'.$path; 
        // var_dump($img);
        // die();
        exit(json_encode(array('code'=>0,'msg'=>$img)));
    }
    //编辑影片
    public function edit(){
        $film_id = (int)input('get.film_id');
        $film_info = Film::get_film_info($film_id);
        View::assign('film_info',$film_info);
        $data['channel'] = Label::get_channel();
        $data['charge'] = Label::get_charge();
        $data['area'] = Label::get_area();
        // var_dump($data);
        // die();
        View::assign('data',$data);
        return View::fetch();
    }
    //保存影片
    public function save(){
        $film_id = (int)input('post.film_id');
        $label['channel'] = array((int)(input('post.channel_id')));
        $label['charge'] = array((int)(input('post.charge_id')));
        $label['area'] = array((int)(input('post.area_id')));
        $data['title'] = (input('post.title'));
        $data['url'] = trim(input('post.url'));
        $data['img'] = trim(input('post.img'));
        $data['keywords'] = trim(input('post.keywords'));
        $data['desc'] = trim(input('post.desc'));
        $data['status'] = trim(input('post.status'));
        //判断是否vip影片
        if($label['charge'][0]==6)
        {
            $data['is_vip']=1;
        }else{
            $data['is_vip']=0;
        }
        if($data['title'] == ''){
			exit(json_encode(array('code'=>1,'msg'=>'影片名称不能为空')));
		}
		if($data['url'] == ''){
			exit(json_encode(array('code'=>1,'msg'=>'影片地址不能为空')));
		}
		if($film_id){
            $data['film_id']=$film_id;
            Film::where('film_id',$film_id)->update($data);
            Label::film_have_label_update($label,$film_id);
		}else{
			$data['add_time'] = time();
            $add_id=Film::insertgetid($data);
            Label::film_have_label_insert($label,$add_id);
		}
		exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }
    //删除影片
    public function del(){
        $film_id = (int)input('post.film_id');
        $delete_film = Film::where('film_id',$film_id)->delete();
        $delete_label = FilmHaveLabel::where('film_id',$film_id)->delete();
        if($delete_film&&$delete_label){
            exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>'删除失败')));
        }
    }
}