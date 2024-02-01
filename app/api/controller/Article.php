<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/09/12 15:52
 * file name : Article.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\api\controller;


use app\api\BaseController;
use think\facade\Config;
use think\facade\Db;

class Article extends BaseController
{
    public function index(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $sort = $data['id']?$data['id']:'';
        $where = [['a.status','=',1]];
        if($sort){
            $where[] = ['a.cid','=',$sort];
        }
        $field = 'a.id,a.cid,a.title,a.articleThumbImg,a.updateTime,a.keywords,a.description,a.views,b.name,b.target,b.temp_list,b.temp_archives,count(c.id) as feed';
        $list = Db::name('article')->alias('a')->join('category'.' b ','b.id= a.cid')->leftJoin('feedback'.' c ','c.aid=a.id')->field($field)->where($where)->group('a.id, a.title, b.name')->order(['a.id'=>'desc'])->page($start,$size)->select()->toArray();
        $count = CountTable('article',$where,'a');
        foreach ($list as &$v){
            if(!$v['articleThumbImg']){
                $v['articleThumbImg'] = 'images/default.jpg';
            }
            $v['month'] = date('m',$v['updateTime']);
            $v['day'] = date('d',$v['updateTime']);
            $v['updateTime'] = date('Y-m-d',$v['updateTime']);
        }
        $arr = array('code'=>200,'msg'=>'ok','count'=>$count,'where'=>$where,'data'=>$list);
        echo json_encode($arr);
    }
    public function datalist(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $sortId = $data['cate']?$data['cate']:'';
        $where = [['isShow','=',1]];
        if($sortId){
            $where[] = ['id','=',$sortId];
        }
        $list = FindTable('category',$where);
        $list['list'] = pageTable('article',$start,$size,[['status','=',1],['cid','=',$list['id']]]);
        foreach ($list['list'] as &$v){
            if(!$v['articleThumbImg']){
                $v['articleThumbImg'] = 'images/default.jpg';
            }
            $v['dateTime'] = date('m/d',$v['updateTime']);
            $v['updateTime'] = date('Y-m-d',$v['updateTime']);
        }
        $arr = array('code'=>200,'msg'=>'ok','data'=>$list);
        echo json_encode($arr);
    }
    public function top(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $where = [['a.status','=',1]];
        $field = 'a.id,a.title,a.articleThumbImg,a.updateTime,a.keywords,a.views,b.name,b.target,b.temp_list,b.temp_archives';
        $list = Db::name('article')->alias('a')->leftJoin('category'.' b','b.id=a.cid')->field($field)->where($where)->order(['a.views'=>'desc'])->page($start,$size)->select()->toArray();
        foreach ($list as &$v){
            $v['month'] = date('m',$v['updateTime']);
            $v['day'] = date('d',$v['updateTime']);
            $v['dateTime'] = date('m/d',$v['updateTime']);
            $v['updateTime'] = date('Y-m-d',$v['updateTime']);
        }
        $arr = array('code'=>200,'msg'=>'ok','data'=>$list);
        echo json_encode($arr);
    }
    public function search(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $key = $data['q']?$data['q']:'';
        $where = [['a.status','=',1]];
        if($key){
            $where[] = ['a.title','like','%'.trim($key).'%'];
        }
        $field = 'a.id,a.title,a.articleThumbImg,a.updateTime,a.keywords,a.views,b.name,b.target,b.temp_list,b.temp_archives,count(c.id) as feed';
        $list = Db::name('article')->alias('a')->join('category'.' b ','b.id= a.cid')->leftJoin('feedback'.' c ','c.aid=a.id')->field($field)->where($where)->group('a.id, a.title, b.name')->order(['a.id'=>'desc'])->page($start,$size)->select()->toArray();
        $count = CountTable('article',$where,'a');
        foreach ($list as &$v){
            if(!$v['articleThumbImg']){
                $v['articleThumbImg'] = 'images/default.jpg';
            }
            $v['month'] = date('m',$v['updateTime']);
            $v['day'] = date('d',$v['updateTime']);
            $v['updateTime'] = date('Y-m-d',$v['updateTime']);
        }
        $arr = array('code'=>200,'msg'=>'ok','count'=>$count,'where'=>$where,'data'=>$list);
        echo json_encode($arr);
    }
    public function tags(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $key = $data['tag']?$data['tag']:'';
        $where = [['a.status','=',1]];
        if($key){
            $where[] = ['a.tags','like','%'.$key.'%'];
        }
        $field = 'a.id,a.title,a.articleThumbImg,a.updateTime,a.keywords,a.views,b.temp_archives,b.name,b.target,b.temp_list,b.temp_archives,count(c.id) as feed';
        $list = Db::name('article')->alias('a')->join('category'.' b ','b.id= a.cid')->leftJoin('feedback'.' c ','c.aid=a.id')->field($field)->where($where)->group('a.id, a.title, b.name')->order(['a.id'=>'desc'])->page($start,$size)->select()->toArray();
        $count = CountTable('article',$where,'a');
        foreach ($list as &$v){
            if(!$v['articleThumbImg']){
                $v['articleThumbImg'] = 'images/default.jpg';
            }
            $v['month'] = date('m',$v['updateTime']);
            $v['day'] = date('d',$v['updateTime']);
            $v['updateTime'] = date('Y-m-d',$v['updateTime']);
        }
        $arr = array('code'=>200,'msg'=>'ok','count'=>$count,'where'=>$where,'data'=>$list);
        echo json_encode($arr);
    }
    public function taglist(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $field = 'a.id,a.tag,c.tags,count(c.id) as count';
        $list = Db::name('taglist')->alias('a')->leftJoin('article'.' c ','c.tags LIKE CONCAT(\'%\', a.tag, \'%\')')->field($field)->group('a.id, a.tag')->order(['a.id'=>'desc'])->page($start,$size)->select()->toArray();
        foreach ($list as &$v){
            $v['rand'] = mt_rand(0,10);
        }
        $arr = array('code'=>200,'msg'=>'ok','data'=>$list);
        echo json_encode($arr);
    }
    public function arRand(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $prefix = Config::get('database.connections.mysql.prefix');
        $table = $prefix.'article';
        $table2 = $prefix.'category';
        $sql = "SELECT a.id,a.title,a.articleThumbImg,a.updateTime,a.keywords,a.views,b.temp_archives FROM `$table` as a left join `$table2` as b on a.cid = b.id WHERE a.id >= (SELECT FLOOR(RAND() * (SELECT MAX(id) FROM `$table`))) and a.status = 1 ORDER BY a.id LIMIT $size";
        $list = Db::query($sql);
        foreach ($list as &$v){
            if(!$v['articleThumbImg']){
                $v['articleThumbImg'] = 'images/default.jpg';
            }
            $v['month'] = date('m',$v['updateTime']);
            $v['day'] = date('d',$v['updateTime']);
            $v['updateTime'] = date('Y-m-d',$v['updateTime']);
        }
        $arr = array('code'=>200,'msg'=>'ok','data'=>$list);
        echo json_encode($arr);
    }
    public function attrID(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $aid = $data['aid']?$data['aid']:'';
        $prefix = Config::get('database.connections.mysql.prefix');
        $table = $prefix.'article';
        $table2 = $prefix.'category';
        $sql = "SELECT a.id,a.title,a.articleThumbImg,a.updateTime,a.keywords,a.views,b.temp_archives FROM `$table` as a left join `$table2` as b on a.cid = b.id WHERE FIND_IN_SET($aid,attrId) > 0 and a.status = 1 ORDER BY a.id LIMIT $size";
        $list = Db::query($sql);
        foreach ($list as &$v){
            if(!$v['articleThumbImg']){
                $v['articleThumbImg'] = 'images/default.jpg';
            }
            $v['month'] = date('m',$v['updateTime']);
            $v['day'] = date('d',$v['updateTime']);
            $v['updateTime'] = date('Y-m-d',$v['updateTime']);
        }
        $arr = array('code'=>200,'msg'=>'ok','data'=>$list);
        echo json_encode($arr);
    }
}