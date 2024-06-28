<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2024/06/22 20:22
 * file name : Database.php
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

class Database extends BaseController
{
    public function index(){
        $dir = backupDatabasePath();
        if(!file_exists($dir)){
            mkdir($dir,0755,true);
        }
        $filteredItems = dirList($dir);
        View::assign('filesql',$filteredItems);
        return View();
    }

    public function dataList(){
        $list = Db::query('show table status');
        $arr = array('code'=>200,'msg'=>'ok','count'=>count($list),'data'=>$list);
        echo json_encode($arr);
    }

    public function export(){
        $par = request()->param();
        $arr = ['code'=>300,'msg'=>'请选择要备份的数据表'];
        $name = date('Ymd-His');
        if($par){
            foreach ($par['data'] as $v){
                $arr = backup($v,$name);
            }
        }
        echo json_encode($arr);
    }

    public function optimize(){
        $par = request()->param();
        $arr = ['code'=>300,'msg'=>'操作失败，请选择要操作的数据！'];
        $res = '';
        if($par){
            if(is_array($par['data'])){
                foreach ($par['data'] as $v){
                    $res = Db::query("OPTIMIZE TABLE `{$v}`");
                }
            }else{
                $res = Db::query("OPTIMIZE TABLE `{$par['data']}`");
            }
            if($res){
                $arr = ['code'=>200,'msg'=>'完成操作！'];
            }
        }
        echo json_encode($arr);
    }

    public function repair(){
        $par = request()->param();
        $arr = ['code'=>300,'msg'=>'操作失败，请选择要操作的数据！'];
        $res = '';
        if($par){
            if(is_array($par['data'])){
                foreach ($par['data'] as $v){
                    $res = Db::query("REPAIR TABLE `{$v}`");
                }
            }else{
                $res = Db::query("REPAIR TABLE `{$par['data']}`");
            }
            if($res){
                $arr = ['code'=>200,'msg'=>'完成操作！'];
            }
        }
        echo json_encode($arr);
    }

    public function restore(){
        $data = request()->param();
        $path = backupDatabasePath().$data['data'];
        $info = pathinfo($path);
        $sql = $info['extension']=='sql'?file_get_contents($path):read_gz($path);
        $sql_list = mini_parse_sql($sql,0,env('database.prefix'));
        $res = redSql($sql_list);
        echo json_encode($res);
    }

    public function delAll(){
        $data = request()->param();
        $res = '';
        if($data){
            if(is_array($data['data'])){
                foreach ($data['data'] as $v){
                    $path = backupDatabasePath().$v;
                    if(file_exists($path)){
                        unlink($path);
                    }
                }
            }else{
                $path = backupDatabasePath().$data['data'];
                if(file_exists($path)){
                    unlink($path);
                }
            }
        }
        $arr = ['code'=>200,'msg'=>'完成操作！'];
        echo json_encode($arr);
    }

    public function sql(){
        return View();
    }
    public function saveAt(){
        $sql = request()->param('sql');
        if($sql){
            try{
                Db::query($sql);
            }catch (\Exception $e){
                return json_encode(['code'=>300,'msg'=>$e->getMessage()]);
            }
        }
        return json_encode(['code'=>200,'msg'=>'执行成功！','sql'=>$sql]);
    }

    public function rep(){
        $table = Db::query('show table status');
        View::assign('table',$table);
        return View();
    }

    public function columns(){
        $data = request()->param();
        $columns = Db::query('show columns from '.$data['table']);
        return json_encode(['code'=>200,'msg'=>'获取成功！','data'=>$columns]);
    }

    public function repcon(){
        $data = request()->param();
        if($data['field'] && $data['old'] && $data['new']){
            try{
                $sql = "UPDATE ".$data['table']." set ".$data['field']."=Replace(".$data['field'].",'".$data['old']."','".$data['new']."') where 1=1 ". $data['where'];
                Db::query($sql);
            }catch(\Exception $e){
                return json_encode(['code'=>300,'msg'=>$e->getMessage()]);
            }
        }
        return json_encode(['code'=>200,'msg'=>'执行完成！']);
    }

}