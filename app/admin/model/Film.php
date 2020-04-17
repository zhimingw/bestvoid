<?php
namespace app\admin\model;

use think\Model;
/*影片表*/

class Film extends Model
{
    static public function get_all()
    {
        $film = Film::select();
        return $film->toArray();
    }
    static public function get_film_info($film_id)
    {
        $film_info = Film::where('film_id',$film_id)->find();
        return $film_info->toArray();
    }
}
