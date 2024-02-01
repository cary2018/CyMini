<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/09/24 14:14
 * file name : Link.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\api\controller;


use app\api\BaseController;
use think\facade\Db;

class Link extends BaseController
{
    public function index(){
        $size = 10;
        $start = 0;
        $where = [['enable','=',1]];
        $list = pageTable('link',$start,$size,$where);
        $arr = array('code'=>200,'msg'=>'ok','data'=>$list);
        echo json_encode($arr);
    }
}