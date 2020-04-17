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
    static public function film_have_label_insert($label,$add_id){
        $result = [];
        foreach($label as $key=>$v){
            $result = array_merge($v,$result);
        }
        $data['label_id']=json_encode($result,true);
        $data['film_id']=$add_id;
        FilmHaveLabel::insert($data);
    }
    static public function film_have_label_update($label,$update_id){
        $result = [];
        foreach($label as $key=>$v){
            $result = array_merge($v,$result);
        }
        $data['label_id']=json_encode($result,true);
        FilmHaveLabel::where('film_id',$update_id)->update($data);
    }
}
