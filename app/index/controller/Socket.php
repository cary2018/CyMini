<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/04/09 17:56
 * file name : Socket.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\index\controller;


use app\BaseController;
use PHPSocketIO\SocketIO;
use think\facade\View;
use Workerman\Worker;

class Socket extends BaseController
{
    public function index(){
        $data = GetSe('userInfo');
        $port = Cfg('port');
        if(!$data){
            return redirect((string)url('index/socket/login'));
        }
        View::assign('data',$data);
        View::assign('port',$port);
        if(request()->isMobile()){
            return View('m_index');
        }
        return View();
    }
    public function upload(){
        $res = UploadImg('file',1,'chat');
        $data = [];
        if($res['code']==200){
            if($res['ident'] == 1){
                $res['code'] = 300;
                echo json_encode($res,JSON_UNESCAPED_UNICODE);
                die;
            }else{
                $data['Img']=$res['result']['img'];
                $data['ThumbImg']=$res['result']['thumb'];
            }
        }
        $arr = ['code'=>200,'msg'=>'上传完成','data'=>$data];
        echo json_encode($arr);
    }
    public function login(){
        $uid = mt_rand(100,999).date('His',time()).uniqid();
        $port = Cfg('port');
        View::assign('uid',$uid);
        View::assign('port',$port);
        return View();
    }
    public function check(){
        $data = request()->param();
        SetSe('userInfo',$data);
        $msg = [
            'code'=>200,
            'msg'=>'登录成功',
            'jump_url'=>'/index/socket'
        ];
        echo json_encode($msg,JSON_UNESCAPED_UNICODE);
    }
    public function service(){
        // 创建socket.io服务端，监听3120端口
        $io = new SocketIO(3120);
        // 当有客户端连接时打印一行文字
        $io->on('connection', function($socket)use($io){
            echo "new connection coming\n";
        });

        Worker::runAll();
    }
}