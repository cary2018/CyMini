<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/10/11 16:53
 * file name : Callback.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\api\controller;


use app\api\BaseController;

class Callback extends BaseController
{
    public function index(){
        $sign_key = Cfg('sign_key');
        if($sign_key){
            $data = request()->param();
            $data_sign = $data['sign'];
            unset($data['sign']);
            $sign = signMd5($data,$sign_key);
            if($sign != $data_sign){
                return '签名错误！';
            }
            $pid = [1=>23,11=>15,2=>16,8=>17,9=>18,10=>5];
            $arr = [
                'cid'=>$data['ar_pid'],
                'title'=>$data['ar_title'],
                'keywords'=>$data['ar_keywords'],
                'description'=>$data['ar_description'],
                'content'=>$data['ar_content'],
                'status'=>$data['ar_status'],
                'orderSort'=>$data['ar_ordery'],
                'downloadJur'=>$data['down_jurisdiction'],
                'createTime'=>time(),
                'updateTime'=>time(),
            ];
            $img = upload('file',1);
            if($img['code']==200){
                if($img['ident'] != 1){
                    $arr['articleImg']=$img['result']['img'];
                    $arr['articleThumbImg']=$img['result']['thumb'];
                }
            }
            //保存数据
            SaveAt('article',$arr);
            return 'success';
        }else{
            return 'refuse';
        }
    }
}