<?php
namespace app\admin\model;
use think\Model;
class Label extends Model
/*标签表*/
{
    static public function get_channel(){
        $Label = new Label();
        $channel = $Label->where('flag','channel')->order('ord')->select();
        return $channel->toArray();
    }
    static public function get_charge(){
        $Label = new Label();
        $charge = $Label->where('flag','charge')->order('ord')->select();
        return $charge->toArray();
    }
    static public function get_area(){
        $Label = new Label();
        $area = $Label->where('flag','area')->order('ord')->select();
        return $area->toArray();
    }
}
