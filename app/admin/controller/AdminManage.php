<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\View;
use app\BaseController;
use app\admin\model\Admin;
use app\admin\model\AdminHaveIdentity;
use app\admin\model\AdminIdentity;
/*管理员管理*/
class AdminManage extends BaseController
{
    /*显示管理员列表*/
    public function index(){
        $admin = new Admin();
        $adminHaveIdentity = new AdminHaveIdentity();
        $adminIdentity = new AdminIdentity();
        $param = Request::param();
        if(isset($param['status']) && $param['status'] == 1){
            $where['status'] = 1;
        }else if(isset($param['status']) && $param['status'] == 2){
            $where['status'] = 2;
        }else{
            $where = true; 
        }
        $p = isset($param['p']) ? $param['p'] : 1;
        // 统计总数
        $count = $admin->where($where)->count();
        $list = $admin
            ->where($where)
            ->order('add_time DESC')
            ->order('id DESC')
            ->page($p,3)
            ->select();
        $right = $list->toArray();
        //查询管理员拥有的身份
        foreach($right as $key=>&$v){
        $v['identity_id'] = $adminHaveIdentity->where('admin_id',$v['id'])->column('identity_id');
        $v['identity_id']=json_decode($v['identity_id'][0],true);
        }
        foreach($right as $key=>&$v){
            $v['identity']= $adminIdentity->where('identity_id','in',$v['identity_id'])->column('title');
        }
        
        $session = session('admin');
        $level = $session['level'];
        $id = $session['id'];
        View::assign([
            'right' => $right,
            'count' => ceil($count/3),
            'p' => $p,
            'status' => isset($param['status']) ? $param['status'] : 0,
            'level' =>$level,
            'id'=>$id
        ]);
        return View::fetch();
    }
    /*添加管理员*/
    public function add(){
        $adminIdentity = new AdminIdentity();
        $adminIdentity = $adminIdentity->select()->toArray();
        $session = session('admin');
        $level = $session['level'];
        View::assign([
            'adminIdentity'=>$adminIdentity,
            'level'=>$level
        ]);
        // var_dump($adminIdentity);
        // die();
        return View::fetch();
    }
    /*保存管理员*/
    public function save(){
        $id = trim(input('post.id'));
        $adminGroups['account'] = trim(input('post.username'));
        $adminGroups['pwd'] = trim(input('post.pass'));
        $adminGroups['real_name'] = trim(input('post.real_name'));
        $adminGroups['level'] = trim(input('post.level'));
        $identity_id = input('post.identity');
        foreach($identity_id as $key=>&$v){
            $v = json_decode($v,true);
        }
        $identity_id=json_encode(array_values($identity_id));
        // var_dump($identity_id);
        // die();
        $adminGroups['add_time'] = time();
        $adminGroups['status'] = trim(input('post.status'));
        /*检测用户名是否已经存在*/
        $admin2 = new Admin();
        $item = $admin2->where(array('account'=>$adminGroups['account']))->find();
        if($item){
            exit(json_encode(array('code'=>1,'msg'=>'用户名已经存在')));
        }
        /*编辑管理员*/
        if($id>0){
            $result = $admin2->where('id',$id)->update($adminGroups);
            if (!$result) {
                exit(json_encode(array('code' => 1, 'msg' => '管理员修改失败')));
            }
            exit(json_encode(array('code' => 0, 'msg' => '修改成功')));
        }
        /*添加管理员*/
        else{
            $result = $admin2->insert($adminGroups);
            //将管理员id,拥有的身份id记入联系表
            $adminHaveIdentity = new AdminHaveIdentity();
            $record = $admin2->where('account',$adminGroups['account'])->find();
            $insert['admin_id'] = $record['id'];
            $insert['identity_id'] = $identity_id;
            // var_dump($insert);
            // die();
            $insert = $adminHaveIdentity->insert($insert);
            if (!$insert) {
                exit(json_encode(array('code' => 1, 'msg' => '管理员添加失败')));
            }
            exit(json_encode(array('code' => 0, 'msg' => '添加成功')));
        }
    }
    /*管理员编辑*/
    public function edit()
    {
        $session = session('admin');
        $level = $session['level'];
        $id = (int)input('get.id');
        $admin = new Admin();
        $admin = $admin->where('id', $id)->find()->toArray();
        //查询管理员拥有的身份
        // $adminHaveIdentity = new adminHaveIdentity();
        $adminIdentity = new AdminIdentity();
        $adminIdentity = $adminIdentity->select()->toArray();
        // $admin['identity_id']=$adminHaveIdentity->where('admin_id',$admin['id'])->column('identity_id');
        // $admin['identity_id']=json_decode($admin['identity_id'][0],true);
        // var_dump($admin['identity_id']);
        // die();
        // $identity = [];//用于存放管理员身份
        // foreach($admin['identity_id'] as $key=>&$v){
        //     $identity[$key]= $adminIdentity->where('identity_id',$v)->column('title');
        // }
        // var_dump($adminIdentity);
        // die();
        View::assign([
            'admin' => $admin,
            'level' => $level,
            'adminIdentity' =>$adminIdentity
            ]);
        return View::fetch();

    }
    /*删除管理员*/
    public function del()
    {
        $id = (int)input('post.id');
        $admin = new Admin();
        $del = $admin->where('id',$id)->find()->delete();
        if(!$del){
            exit(json_encode(array('code'=>1,'msg'=>'删除失败')));
        }else{
            exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
        }
    }
}