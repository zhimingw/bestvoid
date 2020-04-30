<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\View;
use app\BaseController;
use app\admin\model\Banner;

class BannerManage extends BaseController
{
    //显示幻灯片
    public function index()
    {
        $banner = Banner::getAll();
        View::assign([
            'banner'=>$banner
            ]);
        return View::fetch();
    }
    //添加幻灯片
    public function add()
    {
        return View::fetch();
    }
    //编辑影片
    public function edit(){
        $Banner_id = (int)input('get.id');
        $BannerInfo = Banner::getBannerInfo($Banner_id);
        View::assign('BannerInfo',$BannerInfo);
        return View::fetch();
    }
     //幻灯片图片上传
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
    //保存幻灯片
    public function save(){
        $film_id = (int)input('post.film_id');
        $data['title'] = (input('post.title'));
        $data['url'] = trim(input('post.url'));
        $data['img'] = trim(input('post.img'));
        if($data['title'] == ''){
			exit(json_encode(array('code'=>1,'msg'=>'幻灯片名称不能为空')));
		}
		if($data['url'] == ''){
			exit(json_encode(array('code'=>1,'msg'=>'url地址不能为空')));
		}
		if($film_id){
            $data['film_id']=$film_id;
            Banner::where('film_id',$film_id)->update($data);
            Banner::film_have_label_update($film_id);
		}else{
            $add=Banner::insert($data);
		}
		exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }
    //删除幻灯片
    public function del(){
        $id = (int)input('post.id');
        $delete_film = Banner::where('id',$id)->delete();
        if($delete_film){
            exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>'删除失败')));
        }
    }
}