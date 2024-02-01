<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/04/19 23:47
 * file name : Category.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\admin\controller;


use app\admin\BaseController;
use think\facade\Db;
use think\facade\View;
use app\admin\model\Category as Model;

class Category extends BaseController
{
    public function index(){
        return View();
    }
    public function dataList(){
        $tree = GetMenu('category');
        $count = CountTable('category');
        View::assign('tree',$tree);
        $arr = array('code'=>0,'msg'=>'ok','count'=>$count,'limit'=>[],'data'=>$tree);
        echo json_encode($arr);
    }
    public function add(){
        $id = request()->param('id');
        $tree = GetMenu('category');
        foreach ($tree as $k=>$v){
            $level = $v['level']-1;
            if( $level > 1){
                $tree[$k]['p'] = str_repeat("&nbsp;&nbsp;&nbsp;",$level).'|--';
            }else{
                $tree[$k]['p']='';
            }
        }
        View::assign('tree',$tree);
        View::assign('id',$id);
        return View();
    }
    public function edit(){
        $id = request()->param('id');
        $tree = GetMenu('category');
        $data = FindTable('category',[['id','=',$id]]);
        foreach ($tree as $k=>$v){
            $level = $v['level']-1;
            if( $level > 1){
                $tree[$k]['p'] = str_repeat("&nbsp;&nbsp;&nbsp;",$level).'|--';
            }else{
                $tree[$k]['p']='';
            }
        }
        View::assign('tree',$tree);
        View::assign('data',$data);
        return View();
    }
    public function saveAt(){
        $data = request()->param();
        $model = new Model();
        $result = $model->dataSave($data);
        //设置前台导航api缓存
        navApi();
        echo $result;
    }
    public function switchAt(){
        $id = request()->param('id');
        $field = request()->param('field');
        $result = SwitchUp('category',$field,$id);
        //设置前台导航api缓存
        navApi();
        echo json_encode($result);
    }
    public function delAll(){
        $id = request()->param('data');
        $data = Db::name('category')->where('id',$id)->find();
        if($data){
            if(ArrTree('category',$id)){
                $msg = ['status'=>300,'msg'=>lang('error_column_message')];
            }else{
                Db::name('category')->delete($id);
                //设置前台导航api缓存
                navApi();
                $msg = ['code'=>200,'msg'=>lang('delete_message')];
            }
        }else{
            $msg = ['code'=>300,'msg'=>lang('fail_message'),'data'=>$id];
        }
        echo json_encode($msg);
    }
    public function updateField(){
        $data = request()->param();
        $msg = FieldUpdate('category',$data);
        //设置前台导航api缓存
        navApi();
        echo json_encode($msg);
    }
}