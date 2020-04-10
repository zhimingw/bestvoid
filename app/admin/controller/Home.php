<?php
namespace app\admin\controller;

use think\facade\View;
use app\admin\controller\BaseAdmin;
use app\admin\model\Admin;
use app\admin\model\AdminIdentity;
use app\admin\model\AdminHaveIdentity;
use app\admin\model\AdminMenus;
use app\admin\model\IdentityHavePermission;
/*
 * 后台首页
 */
class Home extends BaseAdmin
{
    public function index(){
        $admin = new Admin();
        $adminIdentity = new AdminIdentity();
        $menu = new AdminMenus();
        $adminHaveIdentity = new adminHaveIdentity();
        $IdentityHavePermission = new IdentityHavePermission();
        $session = session('admin');
        $admin=$admin->where('id',$session['id'])->find();
        //筛选出要显示的菜单
        $identity_id = []; //存储管理员拥有的身份id;
        $identity_id = $adminHaveIdentity->where('admin_id',$admin['id'])->column('identity_id');
        $identity_id=json_decode($identity_id[0],true);
        $identity =[];  //存储管理员拥有的身份
        if($identity_id){
            foreach($identity_id as $key=>$v){
            $identity[$key] = $IdentityHavePermission->where('identity_id',$v)->column('menus_id');
        }
        foreach($identity as $key=>&$v){
        $v = (isset($identity) && $identity) ?json_decode($v[0],true):[];
        }
        // var_dump($identity);
        // die();
        $result = []; //如果有多个身份
        foreach($identity as $key=>&$v){
            $result = array_merge($v,$result);
        }
        // var_dump($result);
        // die();
    }
        if($result){
            $where = 'mid in('.implode(',',$result).') and ishidden=1 and status=1';
            $item = $menu->where('pid',0)->where($where)->select()->toArray();
        }
        //分级菜单
        foreach($item as &$v) {
            $son = $menu->where($where)->where('pid', $v['mid'])->select();
            $son = $son->toArray();
            $v['son'] = $son;
        }
        View::assign([
            'admin'=>$admin,
            'item'=>$item
        ]);
        return View::fetch();
    }
    public function welcome(){
        return View::fetch();
    }
}