<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/05/19 20:52
 * file name : Banner.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\api\controller;

use app\api\BaseController;

class Banner extends BaseController
{
    public function index(){
        $data = AllTable('banner',[['enable','=',1]],['orderSort'=>'desc']);
        $msg = ['code'=>200,'msg'=>lang('delete_message'),'data'=>$data];
        echo json_encode($msg);
    }
}