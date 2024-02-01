<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/05/26 16:31
 * file name : Album.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */

namespace app\api\controller;

use app\api\BaseController;
use think\facade\Db;

class Album extends BaseController
{
    public function index(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $where = [['status','=',1]];
        $list = pageTable('album',$start,$size,$where);
        $count = CountTable('album',$where);
        $arr = array('code'=>200,'msg'=>'ok','count'=>$count,'where'=>$where,'data'=>$list);
        echo json_encode($arr);
    }
}