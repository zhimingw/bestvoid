<?php
namespace app\admin\model;

use think\Model;
use think\facade\Request;
/*影片表*/

class Film extends Model
{
    static public function get_page(){
        $film = Film::paginate([
            'list_rows'=>2,
            'query' =>Request::param()
        ]);
        return $film;
    }
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
