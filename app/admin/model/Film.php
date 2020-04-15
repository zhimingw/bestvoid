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
}
