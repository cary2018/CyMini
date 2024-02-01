<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/06/26 15:33
 * file name : Navigation.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */

namespace app\api\controller;

use app\api\BaseController;
use think\facade\Db;

class Navigation extends BaseController
{
    public function index(){
        $pass = request()->param('pass');
        if($pass == '123..'){
            SetSe('access_navigation',$pass);
        }else{
            $data = array();
            $msg = ['code'=>300,'message'=>'访问密码错误！','data'=>$data,'acc'=>$pass];
        }
        $acc =  GetSe('access_navigation');
        if($acc){
            $data = GetCache('navigation');
            if(!$data){
                $data = AllTable('classify',['status'=>1],['orderBy'=>'desc']);
                foreach ($data as $k=>$v){
                    $data[$k]['nav'] = AllTables('navigation',[['cid','=',$v['id']],['is_show','=',1]],$v['number'],['orderBy'=>'desc']);
                }
            }
            $msg = ['code'=>200,'message'=>'访问成功！','data'=>$data,'acc'=>$acc];
        }
        echo json_encode($msg);
    }
}