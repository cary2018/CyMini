<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/09/25 13:11
 * file name : Detail.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\api\controller;


use app\api\BaseController;
use think\facade\Db;

class Detail extends BaseController
{
    public function index(){
        $id = request()->param('id');
        Db::name('article')->where('id', $id)->inc('views')->update();
        $data = FindTable('article',[['id','=',$id],['status','=',1]]);
        $data['feed'] = $data['views'];
        $data['month'] = date('m',$data['updateTime']);
        $data['day'] = date('d',$data['updateTime']);
        $data['updateTime'] = date('Y-m-d',$data['updateTime']);
        $data['tags'] = explode(',',$data['tags']);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    //上一篇
    public function prev(){
        $par = request()->param();
        $data = FindTable('article',[['status','=',1],['cid','=',$par['cid']],['id','<',$par['id']]]);
        if($data){
            $data['updateTime'] = date('Y-m-d',$data['updateTime']);
            $data['cate'] = FindTable('category',['id'=>$par['cid']]);
        }else{
            $data['updateTime'] = '';
            $data['cate'] = FindTable('category',['id'=>$par['cid']]);
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    //下一篇
    public function next(){
        $par = request()->param();
        $data = FindTable('article',[['status','=',1],['cid','=',$par['cid']],['id','>',$par['id']]],['id'=>'asc']);
        if($data){
            $data['updateTime'] = date('Y-m-d',$data['updateTime']);
            $data['cate'] = FindTable('category',['id'=>$par['cid']]);
        }else{
            $data['updateTime'] = '';
            $data['cate'] = FindTable('category',['id'=>$par['cid']]);
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    public function cate(){
        $id = request()->param('id');
        $data = FindTable('category',[['id','=',$id],['isShow','=',1]]);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
}