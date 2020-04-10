<?php
namespace app\admin\controller;
namespace app\admin\controller;

use think\facade\View;
use app\admin\model\AdminIdentity;
use app\admin\model\AdminMenus;
use app\admin\model\IdentityHavePermission;
use app\admin\controller\BaseAdmin;

class Roles extends BaseAdmin
{
    public function index(){
        $adminIdentity = new AdminIdentity();
        $adminIdentity = $adminIdentity->order('identity_id desc')->select();
        View::assign('adminIdentity',$adminIdentity);
        return View::fetch();
    }
    public function add(){
        $adminIdentity = new AdminIdentity();
        //加载管理员已经有的权限
        $identity_id = (int)input('get.identity_id');
        if($identity_id){
        $role = $adminIdentity->where('identity_id',$identity_id)->find();
        // var_dump($role->toArray());
        // die();
        $identityHavePermission = new IdentityHavePermission();
        $identityHavePermission = $identityHavePermission->where('identity_id',$identity_id)->column('menus_id');
        $identityHavePermission = json_decode($identityHavePermission[0],true);
        // var_dump($identityHavePermission);
        // die();
        View::assign([
            'role'=>$role,
            'identityHavePermission'=>$identityHavePermission
            ]);
        }else{
            $identity_id=0;
        }
        //加载全部菜单
        $menu = new AdminMenus();
        $parent = $menu->where('pid',0)->where('status',1)->select();
        $parent = $parent->toArray();
        foreach($parent as &$v){
            $son = $menu->where('status',1)->where('pid',$v['mid'])->select();
            $son = $son->toArray();
            foreach($son as $key =>&$vv){
                $grandson = $menu->where('status',1)->where('pid',$son[$key]['mid'])->select();
                $grandson = $grandson->toArray();
                $vv['son']=$grandson;
            }
            $v['son'] = $son;
        }
//        var_dump($parent);
        View::assign([
            'identity_id'=>$identity_id,//添加身份的情况时
            'menu'=>$parent
            ]);
        return View::fetch();
    }
    public function save(){
        $adminIdentity = new AdminIdentity();
        $identityHavePermission = new IdentityHavePermission();
        $identity_id = (int)input('post.identity_id');//编辑身份identity_id
        $data['title'] = trim(input('post.title'));
        $menus = input('post.menu');
        if(!$data['title']){
            exit(json_encode(array('code'=>1,'msg'=>'角色名称不能为空')));
        }
        $menus[] = json_encode(array_keys($menus));
        $menus_id['menus_id'] = end($menus);
        // var_dump($menus_id,$data);
        // die();
        if($identity_id)//如果是编辑身份权限的情况
        {
            $adminIdentity->where('identity_id',$identity_id)->update($data);
            $identityHavePermission->where('identity_id',$identity_id)->update($menus_id);
        }else {
            $identity_id = $adminIdentity->insertgetid($data);
            $menus_id['identity_id'] = $identity_id;
            $identityHavePermission->insert($menus_id);
        }
        exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }
    public function deletes(){
        $identity_id = (int)input('identity_id');
        $adminIdentity = new AdminIdentity();
        $adminIdentity->where('identity_id',$identity_id)->delete();
        exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
    }
}
