<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/06/26 15:15
 * file name : Navigation.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\index\controller;

use app\index\BaseController;

class Navigation extends BaseController
{
    public function index(){
        $id = request()->param('id');
        if($id){
            $data = FindTable('category',[['id','=',$id],['isShow','=',1]]);

        }else{

        }
        return View();
    }
}