<?php
namespace app\admin\controller;

use think\facade\View;
use app\BaseController;
use app\admin\model\Admin;

class Account extends BaseController
{
    public function login()
    {
        return View::fetch();
    }
    //管理员登录
    public function dologin()
    {
        $time = time();
        $username = trim(input('post.username'));
        $pwd = trim(input('post.pwd'));
        $verifycode = trim(input('post.verifycode'));

        if($username == ''){
            exit(json_encode(array('code'=>1,'msg'=>'用户名不能为空')));
        }
        if($pwd == ''){
            exit(json_encode(array('code'=>1,'msg'=>'密码不能为空')));
        }
        if($verifycode == ''){
            exit(json_encode(array('code'=>1,'msg'=>'请输入验证码')));
        }
        // 验证验证码
        if(!captcha_check($verifycode)){
            exit(json_encode(array('code'=>1,'msg'=>'验证码错误')));
        }
        // 验证用户
        $admin = new Admin();
        $item = $admin::where('account', $username)->find();
        if($item) {
            $item = $item->toArray();
            //时间次数
            $item['last_time'] = $time;
            $item['login_count'] = $item['login_count'] + 1;
        }
//        dump($admin);
//        die();
        //获取ip地址
        $server_hostname=gethostname();
        $server_hostname .= ".";
        $server_ip=gethostbyname($server_hostname);
        $item['last_ip'] = $server_ip;
        if(!$item){
            exit(json_encode(array('code'=>1,'msg'=>'用户不存在')));
        }
        if($pwd != $item['pwd']){
            exit(json_encode(array('code'=>1,'msg'=>'密码错误')));
        }
        if($item['status'] == 0){
            exit(json_encode(array('code'=>1,'msg'=>'用户已被禁用')));
        }
        // 设置用户session
        session('admin',$item);
//        $s = session('admin');
//        dump($s);
        $admin->where('id', $item['id'])->update($item);
        return json(array('code'=>0,'msg'=>'登录成功'));
    }

    public function logout(){
        session('admin',null);
        exit(json_encode(array('code'=>0,'msg'=>'退出成功')));
    }
}
