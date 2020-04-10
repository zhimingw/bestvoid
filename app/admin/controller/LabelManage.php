<?php
/*
标签管理
*/
namespace app\admin\controller;

use app\admin\controller\BaseAdmin;
use think\facade\View;
use app\admin\model\Label;

class LabelManage extends BaseAdmin
{
    //频道管理
    public function channel(){
        $channel = Label::get_channel();
        // var_dump($channel);
        // die();
        View::assign('channel',$channel);
        return View::fetch();
    }
    //资费管理
    public function charge(){
        $charge = Label::get_charge();
        View::assign('charge',$charge);
        return View::fetch();
    }
    //地区
    public function area(){
        $area = Label::get_area();
        View::assign('area',$area);
        return View::fetch();
    }
    /*保存标签*/
    public function save()
    {
        $flag = input('post.flag');
        $label_id = input('post.label_id');
        $ord = input('post.ord');
        $title = input('post.title');
        $status = input('post.status');
        foreach ($ord as $key => $value) {
            $data['flag'] = $flag;
            $data['ord'] = $value;
            $data['title'] = $title[$key];
            $data['status'] = isset($status[$key]) ? 0 : 1;
            // var_dump($data);
            // die();
            /*添加一条*/
            if ($key == 0 && $data['title']) {
                Label::insert($data);
            }
            if ($key > 0) {
                if ($data['title'] == '') {
                    //删除
                    Label::where('label_id', $key)->delete();
                } else {
                    //修改
                    Label::where('label_id', $key)->update($data);
                }
            }
        }
        exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }
}
