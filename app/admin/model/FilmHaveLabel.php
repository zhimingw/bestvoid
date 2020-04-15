<?php
namespace app\admin\model;

use think\Model;
use app\admin\model\Film;
use app\admin\model\Label;

/*影片拥有标签表*/

class FilmHaveLabel extends Model
{
    /*获得影片对应标签*/
    static public function get_film_label(){
        $film = Film::get_all();
        foreach($film as $key => &$v){
            $v['label_id'] = FilmHaveLabel::where('film_id',$v['film_id'])->column('label_id');
            $v['label_id'] = json_decode($v['label_id'][0],true);
        }
        // //将label_id装换为对应的字符
        foreach($film as $key => &$v){
        $where = 'label_id in('.implode(',',$v['label_id']).') and status=1';
        $v['label'] = Label::where($where)->column('title,flag');
        }
        return $film;
    }
}
