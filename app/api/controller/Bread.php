<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/09/24 19:51
 * file name : Bread.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\api\controller;


use app\api\BaseController;

class Bread extends BaseController
{
    public function index(){
        $id = request()->param('id');
        $tree = GetMenu('category');
        $new = upTree($tree,$id);
        $arr = ['code'=>200,'data'=>array_reverse($new)];
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }
    public function breadDetail(){
        $id = request()->param('id');
        $data = FindTable('article',[['id','=',$id],['status','=',1]]);
        $cate = FindTable('category',[['id','=',$data['cid']],['isShow','=',1]]);
        $tree = GetMenu('category');
        $new = upTree($tree,$data['cid']);
        $arr = ['code'=>200,'cate'=>$cate,'article'=>$data,'data'=>array_reverse($new)];
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }
}