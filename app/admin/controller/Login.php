<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/11/25 23:12
 * file name : Login.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */

namespace app\admin\controller;

use app\admin\BaseController;
use app\admin\validate\Login as LoginValidate;
use think\exception\ValidateException;
use think\facade\View;

class Login extends BaseController
{
    public function index(){
        $sys = Cfg('sys_title')?Cfg('sys_title'):'';
        View::assign('sys',$sys);
        $session = GetSe('admin');
        if($session){
            return redirect((string)url('/admin'));
        }
        return View();
    }
    public function check(){
        $data = request()->param();
        try {
            validate(LoginValidate::class)->check($data);
            $user = FindTable('admin',[['username','=',$data['username']],['status','=',1]]);
            //生成 token 防止验证失败 token 失效
            $token = request()->buildToken('__token__', 'sha1');
            if($user){
                if(PasswordVerify($data['password'],$user['password'])){
                    //保存登录信息
                    SetSe('admin',$user);
                    $msg = ['code'=>200,'msg'=>'登录成功','jump_url'=>'/admin'];
                }else{
                    $msg = ['code'=>300,'msg'=>'用户名或密码错误！！','token'=>$token];
                }
            }else{
                $msg = ['code'=>300,'msg'=>'用户名或密码错误！','token'=>$token];
            }
        } catch (ValidateException $e) {
            //生成 token 防止验证失败 token 失效
            $token = request()->buildToken('__token__', 'sha1');
            // 验证失败 输出错误信息
            $msg = ['code'=>300,'msg'=>$e->getError(),'token'=>$token];
        }
        echo json_encode($msg);
    }
    public function logout(){
        //退出登录
        DelSe('admin');
        return redirect((string)url('/admin/login'));
    }
}