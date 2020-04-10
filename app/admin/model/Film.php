<?php
namespace app\admin\model;

use think\Model;
/*影片表*/

class Film extends Model
{
    static public function get_all()
    {
        $film = Film::where('status',1)->select();
        return $film->toArray();
    }
}
