<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/11/01 21:40
 * file name : Liar.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\api\controller;


use app\api\BaseController;

class Liar extends BaseController
{
    public function index(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $where = [['isShow','=',1]];
        if(array_key_exists('suspect',$data)){
            if($data['suspect']){
                $where[] = ['suspect','like','%'.trim($data['suspect']).'%'];
            }
        }
        $list = pageTable('liar',$start,$size,$where,['id'=>'desc']);
        $count = CountTable('liar',$where);
        foreach ($list as $k=>$v){
            $list[$k]['showImg'] = AllTable('liarImg',['lid'=>$v['id']]);
            $list[$k]['createTime'] = date('Y-m-d H:i:s',$v['createTime']);
        }
        $arr = array('code'=>200,'msg'=>'ok','count'=>$count,'limit'=>$where,'data'=>$list);
        echo json_encode($arr);
    }
}