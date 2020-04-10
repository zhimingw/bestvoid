<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\View;
use app\admin\model\AdminMenus;
use app\admin\controller\BaseAdmin;

class Menu extends BaseAdmin
{
    /*菜单列表*/
    public function index()
    {
        $menu = new AdminMenus();
        /*加载子菜单*/
        $mid = (int)input('get.mid');
//        var_dump($mid);
        $son = $menu->where('pid', $mid)->order('ord')->select();
        if (!$son) {
            $result = $menu->where('pid', 0)->order('ord')->select();
        } else {
            $result = $son;
        }
        $result = $result->toArray();
        /*返回上一级*/
        $backid = 0;
        if ($mid) {
            $parent = $menu->where('mid', $mid)->order('ord')->find();
            $backid = $parent['mid'];
        }
        View::assign([
            'mid' => $mid,
            'result' => $result,
            'backid' => $backid
        ]);
        return View::fetch();
    }

    /*保存菜单*/
    public function save()
    {
        $menu = new AdminMenus();
        $pid = input('post.pid');
        $ord = input('post.ord');
        $title = input('post.title');
        $controller = input('post.controller');
        $method = input('post.method');
        $ishidden = input('post.ishidden');
        $status = input('post.status');
        // var_dump($ord);
        // die();
        foreach ($ord as $key => $value) {
            $data['pid'] = $pid;
            $data['ord'] = $value;
            $data['title'] = $title[$key];
            $data['controller'] = $controller[$key];
            $data['method'] = $method[$key];
            $data['ishidden'] = isset($ishidden[$key]) ? 0 : 1;
            $data['status'] = isset($status[$key]) ? 0 : 1;
            
            /*添加一条*/
            if ($key == 0 && $data['title']) {
                $insert = $menu->insert($data);
            }
            if ($key > 0) {
                if ($data['title'] == '' && $data['controller'] == '' && $data['method'] == '') {
                    //删除
                    $delete = $menu->where('mid', $key)->delete();
                } else {
                    //修改
                    $update = $menu->where('mid', $key)->update($data);
                }
            }
        }
        exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }
}