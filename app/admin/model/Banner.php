<?php
namespace app\admin\model;

use think\model;
use think\facade\Request;

class Banner extends model
{
    //获取幻灯片列表
    static public function getAll(){
        $all = self::order('ord')->select();
        $all = $all?$all->toArray():[];
        return $all;
    }
    //获取幻灯片信息
    static public function getBannerInfo($id)
    {
        $bannerInfo = self::where('id',$id)->find()->toArray();
        return $bannerInfo;
    }
}