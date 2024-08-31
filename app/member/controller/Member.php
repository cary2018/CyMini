<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2024/08/16 21:09
 * file name : Member.php
 * User: asusa
 * Author: Hyy-Cary（优）
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\member\controller;


use app\member\BaseController;
use think\facade\Db;
use think\facade\View;

class Member extends BaseController
{
    public function index(){
        $member = GetSe('MemberCenter');
        $data = Db::name('admin')->where('id',$member['id'])->find();
        View::assign('data',$data);
        return view();
    }
    public function saveAt(){
        $data = request()->param();
        $member = GetSe('MemberCenter');
        $data['id'] = $member['id'];
        $res = UploadImg('headImg',1);
        if($res['code']==200){
            if($res['ident'] == 1){
                $res['code'] = 300;
                echo json_encode($res,JSON_UNESCAPED_UNICODE);
                die;
            }else{
                $data['headImg']=$res['result']['img'];
                $data['thumbImg']=$res['result']['thumb'];
            }
        }
        SaveAt('admin',$data);
        $arr = ['code'=>200,'msg'=>lang('success_message'),'data'=>$data];
        return json_encode($arr,JSON_UNESCAPED_UNICODE);
    }
    public function password(){
        return view();
    }
    public function update(){
        $data = request()->param();
        $member = GetSe('MemberCenter');
        $pass = Db::name('admin')->where('id',$member['id'])->find();
        if(PasswordVerify($data['password'],$pass['password'])){
            if($data['newpassword'] == $data['confpassword']){
                $NewPass = PasswordSet($data['newpassword']);
                //更新密码
                Db::name('admin')->where('id',$member['id'])->update(['password'=>$NewPass]);
                $msg = ['code'=>200,'msg'=>lang('edit_success')];
            }else{
                $msg = ['code'=>300,'msg'=>lang('newpass')];
            }
        }else{
            $msg = ['code'=>300,'msg'=>lang('oldpass')];
        }
        return json_encode($msg,JSON_UNESCAPED_UNICODE);
    }
}