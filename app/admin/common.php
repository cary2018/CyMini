<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/12/16 20:18
 * file name : common.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */
/**
 * @param $arr
 * @return mixed
 */
function TmHtml($arr){
    foreach ($arr as $k=>$v){
        switch ($v['sys_type']){
            case 'input':
                if($v['sys_variable'] == 'view_path'){
                    // 使用 scandir() 函数读取目录
                    $dir = 'template/';
                    if(!file_exists($dir)){
                        mkdir($dir,0755);
                    }
                    $contents = scandir($dir);
                    $filteredItems = array_filter($contents, function ($item) {
                        return $item !== '.' && $item !== '..';
                    });
                    $path = Cfg('view_path');
                    $arr[$k]['sys_html'] = '<select name="content[]">';
                    foreach ($filteredItems as $item){
                        $arr[$k]['sys_html'] .= '<option value="'.$item.'"';
                        $arr[$k]['sys_html'] .= ''.$item==$path?" selected >":"".'>';
                        $arr[$k]['sys_html'] .= $item.'</option>';
                    }
                    $arr[$k]['sys_html'] .= '</select>';
                }else{
                    $arr[$k]['sys_html'] = '<input type="text" name="content[]" value="'.$v['sys_content'].'" class="layui-input" id="nav_url" >';
                }
                break;
            case 'textarea':
                $arr[$k]['sys_html'] = '<textarea name="content[]" id="sys_content" style="height:50px;" cols="50" rows="4" class="layui-input">'.$v['sys_content'].'</textarea>';
                break;
            case 'file';
                $arr[$k]['sys_html'] = '<input type="hidden" name="content[]" value="'.$v['sys_content'].'" class="layui-input" id="nav_url" >';
                $arr[$k]['sys_html'] .= '<img id="imgAmplify" layer-src=/'.$v['sys_content'].' src=/'.$v['sys_content'].'>';
                break;
            case 'bool':
                if($v['sys_content'] == 1){
                    $arr[$k]['sys_html'] = '<input type="radio" name="content[]" value="1" title="是" checked>';
                    $arr[$k]['sys_html'] .= '<input type="radio" name="content[]" value="0" title="否" >';
                }else{
                    $arr[$k]['sys_html'] = '<input type="radio" name="content[]" value="1" title="是" >';
                    $arr[$k]['sys_html'] .= '<input type="radio" name="content[]" value="0" title="否" checked>';
                }
                break;
            case 'number':
                $arr[$k]['sys_html'] = '<input type="text" oninput="value=value.replace(/[^\d]/g,\'\')" name="content[]" value="'.$v['sys_content'].'" class="layui-input" id="number" >';
                break;
        }
    }
    return $arr;
}
/**
 * @param $arr
 * @param int $pid
 * @param array $contrast
 * @param string $pids
 * @param string $id
 * @param int $level
 * @return array
 * 获取菜单
 */
function MenuNode($arr,$pid=0,$contrast=[],$pids = 'pid',$id = 'id',$level=0){
    //初始化儿子
    $child = '';
    $data = array();
    //循环所有数据找$id的儿子
    foreach ($arr as $key => $v) {
        //找到儿子了
        if ($v[$pids] == $pid) {
            //先去掉自己，自己不可能是自己的儿孙
            unset($arr[$key]);
            $son = MenuNode($arr, $v[$id],$contrast,$pids,$id,$level+1);
            //组装数据
            $child = [
                'id'=>$v['id'],
                'title'=>$v['title'],
                'href'=>$v['hrefUrl'],
                'field'=>'menuAuth[]',
                'spread'=>0,
                'level'=>$level,
                'children'=>$son
            ];

            if(in_array($v['id'],$contrast)){
                if($son){
                    $child['checked'] = false;
                }else{
                    $child['checked'] = true;
                }
            }
            //压入数组
            array_push($data,$child);
        }
    }
    return $data;
}

/**
 * @throws Exception
 * 系统日志
 */
function WebLog(){
    $con = request()->controller(true);
    $path = request()->action();
    $url = app('http')->getName().'/'.request()->controller(true).'/'.request()->action();
    $mc = getCer($con);
    $str = $mc.' / '.getAct($path);
    if($con == 'menu'){
        if($path == 'add'){
            $str = $mc.' / 添加';
        }
    }
    if($con == 'welcome'){
        if($path == 'index'){
            $str = $mc.' / 后台首页';
        }
    }
    if($con == 'cache'){
        if($path == 'index'){
            $str = $mc.' / 清理缓存';
        }
    }
    if($con == 'system'){
        if($path == 'index'){
            $str = $mc.' / 参数配置';
        }
        if($path == 'list'){
            $str = $mc.' / 配置项';
        }
        if($path == 'add'){
            $str = $mc.' / 添加';
        }
    }
    $ip = get_client_ip();
    $reg = new Ip2Region();
    $region = $reg->btreeSearch($ip);
    $user = GetSe('admin');
    if($user){
        $name = $user['username'];
    }else{
        $name = '';
    }
    $data = [
        'username'=>$name,
        'ip'=>$ip,
        'region'=>$region['region'],
        'path'=>$url,
        'remark'=>$str,
        'createTime'=>time(),
    ];
    $skip = [
        'index',
        'dataList',
        'add',
        'addc',
        'edit',
        'edits',
        'list',
        'codata',
        'conlist',
        'courl',
        'select',
        'content',
        'importAll',
    ];
    $bool = inArr($path,$skip);
    if(!$bool){
        SaveAt('weblog',$data);
    }
}
//转小写
function inArr($needle, $haystack) {
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}