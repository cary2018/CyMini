<?php
// 应用公共文件
use think\facade\Cache;
use think\facade\Session;
use think\facade\Config;
use think\facade\Db;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
//获取应用下的所有控制器和方法
function AllMethods(){
    //获取应用名称 tp版本：6.1.4
    $app = app('http')->getName();
    //拼接目录地址
    $dir = base_path() . $app . '\controller';
    //列出目录中的文件和目录
    $files = scandir($dir);
    $methods = [];
    $i = 0;
    //过滤方法
    $get_functions = array('initialize', '__construct', 'registerMiddleware', 'beforeAction','fetch','display','assign','filter','engine','validateFailException','validate','__debugInfo','success','error','result','redirect','getResponseType');
    foreach ($files as $file) {
        if (strpos($file, '.php') !== false) {
            $controllerName = str_replace('.php', '', $file);
            $className = '\app\\' . $app . '\controller\\' . $controllerName;
            //echo $className;die;
            //返回方法名
            $met = get_class_methods($className);
            //计算数组的差集
            $result = array_diff($met,$get_functions);
            foreach ($result as $k=>$v){
                $methods[$i]['module'] = $app;
                $methods[$i]['controller'] = $controllerName;
                $methods[$i]['action'] = $v;
                $i++;
            }
        }
    }
    return $methods;
}
/**
 * @param string $mailto 收件地址
 * @param string $nickname  邮件昵称
 * @param string $subject   邮件标题
 * @param string $content   邮件内容
 * @param string $addAttachment
 * 邮件发送
 */
function SendEmail($mailto, $nickname, $subject, $content,$addAttachment='')
{
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                // 调试模式输出
        $mail->isSMTP();                                   // 使用SMTP
        $mail->Host       = Cfg('email_smtp');         // SMTP服务器
        $mail->SMTPAuth   = true;                          // 允许 SMTP 认证
        $mail->Username   = Cfg('email_account');      // SMTP 用户名  即邮箱的用户名
        $mail->Password   = Cfg('email_password');     // SMTP 密码  部分邮箱的授权码(例如163邮箱)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   // 允许 TLS 或者ssl协议
        $mail->Port       = Cfg('email_port');         // 服务器端口 25 或者465 具体要看邮箱服务器支持

        //Recipients
        $mail->setFrom(Cfg('email_account'), 'Mailer');//发件人
        $mail->addAddress($mailto, $nickname);//收件人
        //$mail->addAddress('ellen@example.com');               // 可添加多个收件人
        $mail->addReplyTo('info@example.com', 'Information');//回复的时候回复给哪个邮箱 建议和发件人一致
        //$mail->addCC('cc@example.com');//抄送
        //$mail->addBCC('bcc@example.com');//密送

        //Attachments
        if($addAttachment){
            $mail->addAttachment($addAttachment);//发送附件
            //$mail->addAttachment('D:\web\tp\tp6\public\img/5_copy.jpg');  // 发送附件并且重命名
        }

        //Content
        $mail->isHTML(true);         // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
        $mail->Subject = $subject;          //邮件标题
        $mail->Body    = $content;          //邮件内容
        //果邮件客户端不支持HTML则显示此内容
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        $arr = [
            'code'=>200,
            'message'=>'邮件已发送',
            'data'=>'10000',
        ];
        echo json_encode($arr);
    } catch (Exception $e) {
        $arr = [
            'code'=>200,
            'message'=>'邮件发送失败',
            'data'=>"{$mail->ErrorInfo}",
        ];
        echo json_encode($arr);
    }
}
/**
 * @param $password
 * @return bool|mixed|string
 * 哈唏+MD5加密
 *
 */
function PasswordSet($password)
{
    $password = password_hash(md5($password),PASSWORD_BCRYPT);
    return $password;
}
/**
 * @param $np    //输入的原始密码
 * @param $op   //加密后的原始密码
 * @return bool
 * 哈唏+MD5验证密码
 */
function PasswordVerify($np,$op)
{
    $result = password_verify(md5($np),$op);
    if($result)
    {
        return true;
    }else{
        return false;
    }
}
/**
 * @param string $name  缓存名
 * @param array $data  数据
 * @param int $outTime  有效时间
 * 设置缓存
 */
function SetCaChe($name,$data,$outTime = 30){
    $outTime = (3600*3600)*$outTime;
    Cache::set($name, $data, $outTime);
}

/**
 * @param $name
 * @return mixed
 * 缓存不存在返回空字符串
 * 获取缓存
 */
function GetCache($name){
    return Cache::get($name,'');
}

/**
 * @param $name
 * 删除缓存
 */
function delCache($name){
    Cache::delete($name);
}

/**
 * @param $name
 * @param $value
 * 设置 session
 */
function SetSe($name,$value){
    Session::set($name, $value);
}

/**
 * @param $name
 * @return mixed
 * 不存在返回空字符串
 * 获取 session
 */
function GetSe($name){
    return Session::get($name,'');
}

/**
 * @param $name
 * 删除 session
 */
function DelSe($name){
    Session::delete($name);
}

/**
 * @param $str
 * 获取配置文件所有参数
 */
function CfgInfo($str){
    return Config::get($str);
}
/**
 * @param $str
 * @return mixed
 * 获取配置文件单个参数
 */
function Cfg($str=''){
    return Config::get('web.'.$str);
}
function GetConfig($name,$str){
    return Config::get($name.'.'.$str);
}
/**
 * @param string $name
 * @param float|int $outTime
 * @return array|mixed
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 *
 */
function MenuList($name='Menu',$outTime = 3600*24*30){
    //缓存所有菜单列表
    $menu = caheMenu();
    //缓存后台菜单
    //$cache = GetCache($name);
    $cache = '';
    if($cache){
        $newTree = GetTree($menu,0);
        $cache['menuInfo'] = $newTree;
        return $cache;
    }else{
        //更新后台菜单缓存
        SetMenu();
        return GetCache($name);
    }
}
//菜单列表缓存
function caheMenu(){
    $cache = GetCache('MenuList');
    if($cache){
        return $cache;
    }else{
        $menu = GetMenu('menu',[['isShow','=',1],['ident','<>',1]],'id',['orderSort'=>'desc']);
        SetCaChe('MenuList',$menu);
        return $menu;
    }
}
/**
 * @param string $table  表名
 * @param array $where   条件
 * @param string $field  主键字段名（默认id）
 * @param array $order  排序（支持多字段排序['order','id'=>'desc']）
 * @return \think\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 获取无限菜单数据
 */
function GetMenu($table='menu',$where=[],$field='id',$order=[]){
    $list = Db::name($table)->field("*,concat(path,'-',$field) as bpath")->where($where)->order($order)->order('bpath')->select()->toArray();
    foreach ($list as $key=>$item){
        $level = count(explode('-',$item['path']));
        //加入层级
        $list[$key]['level'] = $level;
    }
    return $list;
}
/**
 * @param string $name
 * @param int $outTime
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 设置菜单缓存
 */
function SetMenu($name='Menu',$outTime = 30){
    $menuList = caheMenu();
    $index = FindTable('menu',[['ident','=',1]]);
    $tree = GetTree($menuList,0);
    $arr = array(
        'homeInfo'=>['title'=>$index['title'],'href'=>$index['hrefUrl']],
        'logoInfo'=>['title'=>Cfg('sys_title'),'href'=>'','image'=>Cfg('sys_logo')],
        'menuInfo'=>$tree,
    );
    SetCaChe($name,$arr,$outTime);
}
/**
 * @param $arr
 * @param int $pid
 * @param string $pids
 * @param string $id
 * @param int $level
 * @return array
 * 返回多维数组（仅供 layuimini 后台菜单使用）
 */
function GetTree($arr,$pid=0,$pids = 'pid',$id = 'id',$level=0){
    $user = GetSe('admin');
    $menu = [];
    if($user && $user['isAdmin'] != 1){
        $menuId = FindTable('admin',[['id','=',$user['id']]]);
        $roleId = implode(',',json_decode($menuId['role'],true));
        $role = AllTable('role',[['status','=',1],['id','in',$roleId]]);
        $authId = [];
        foreach ($role as $v){
            if($v['menuAuth']){
                $json = json_decode($v['menuAuth']);
                foreach($json as $k=>$item){
                    array_push($authId,$item);
                }
            }
        }
        $aid = implode(',',$authId);
        $auth = AllTable('menu',[['isShow','=',1],['id','in',$aid]]);
        foreach ($auth as $val){
            array_push($menu,$val['id']);
        }
        array_unique($menu);
    }
    //初始化儿子
    $child = '';
    $data = array();
    //循环所有数据找$id的儿子
    foreach ($arr as $key => $v) {
        //找到儿子了
        if ($v[$pids] == $pid) {
            //先去掉自己，自己不可能是自己的儿孙
            unset($arr[$key]);
            $son = GetTree($arr, $v[$id],$pids,$id,$level+1);
            //组装数据
            if($user['isAdmin'] == 1){
                $child = [
                    'id'=>$v['id'],
                    'title'=>$v['title'],
                    'href'=>$v['hrefUrl'],
                    'target'=>$v['target'],
                    'icon'=>'fa '.$v['icon'],
                    'level'=>$level,
                    'child'=>$son
                ];
            }else{
                if(in_array($v['id'],$menu)){
                    $child = [
                        'id'=>$v['id'],
                        'title'=>$v['title'],
                        'href'=>$v['hrefUrl'],
                        'target'=>$v['target'],
                        'icon'=>'fa '.$v['icon'],
                        'level'=>$level,
                        'child'=>$son
                    ];
                }else{
                    continue;
                }
            }
            //压入数组
            array_push($data,$child);
        }
    }
    return $data;
}
/**
 * @param $arr
 * @param int $pid
 * @param string $pids
 * @param string $id
 * @param int $level
 * @return array
 * 递归返回多维数据
 */
function MdaTree($arr,$pid=0,$pids = 'pid',$id='id',$level=0){
    $tree = array();
    foreach($arr as $k=>$v){
        if($v[$pids] == $pid){
            $v['lv'] = $level;
            $tree[$k] = $v;
            $tree[$k]['son'] = MdaTree($arr,$v[$id],$pids,$id,$level+1);
        }
    }
    return $tree;
}
/**
 * @param $table
 * @param array $where
 * @param array $order
 * @return array|\think\Model|null
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 查询表返回一条数据
 */
function FindTable($table,$where = [],$order=['id'=>'desc']){
    return Db::name($table)->where($where)->order($order)->find();
}
/**
 * @param $table
 * @param array $where
 * @param string $alias
 * @return int
 * 返回表总数据数
 */
function CountTable($table, $where=[],$alias=''){
    return Db::name($table)->alias($alias)->where($where)->count();
}
/**
 * @param string $table  表名
 * @param int $start  起始页
 * @param int $size   显示数量
 * @param array $where  条件
 * @param string[] $order  排序
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 *
 */
function pageTable($table,$start=0,$size=10,$where=[],$order=['id'=>'desc']){
    return Db::name($table)->where($where)->order($order)->page($start,$size)->select()->toArray();
}

function joinTable($table,$table2,$start=0,$size=10,$where=[],$order=['id'=>'desc']){
    return Db::name($table)->alias('a')->leftJoin($table2.' b ','b.id= a.cid')->field('a.*,b.name as nickname')->where($where)->order($order)->page($start,$size)->select()->toArray();
}
/**
 * @param $table
 * @param array $where
 * @param string[] $order
 * @return array
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 返回所有数据
 */
function AllTable($table,$where=[],$order=['id'=>'desc']){
    return Db::name($table)->where($where)->order($order)->select()->toArray();
}
function AllTables($table,$where=[],$number,$order=['id'=>'desc']){
    return Db::name($table)->where($where)->order($order)->limit($number)->select()->toArray();
}
/**
 * @param $arr
 * @param int $pid
 * @param array $contrast 对比数据
 * @param string $pids
 * @param string $id
 * @param int $level
 * @return array
 * 获取节点数据
 */
function GetNode($arr,$pid=0,$contrast=[],$pids = 'pid',$id = 'id',$level=0){
    //初始化儿子
    $child = '';
    $data = array();
    //循环所有数据找$id的儿子
    foreach ($arr as $key => $v) {
        //找到儿子了
        if ($v[$pids] == $pid) {
            //先去掉自己，自己不可能是自己的儿孙
            unset($arr[$key]);
            $son = GetNode($arr, $v[$id],$contrast,$pids,$id,$level+1);
            //组装数据
            $child = [
                'id'=>$v['id'],
                'title'=>$v['title'],
                'href'=>$v['href'],
                'field'=>'auth[]',
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
 * @param $data
 * @return array
 * 重组 控制器和方法
 */
function arrMac($data){
    $arr = [];
    foreach($data as $k=>$v){
        $model = $v['module'];
        $controller = strtolower($v['controller']);
        $action = $v['action'];
        $mc = $model.'/'.$controller;
        $router = $model.'/'.$controller.'/'.$action;
        if(in_array($mc,$arr)){
            array_push($arr,$router);
        }else{
            array_push($arr,$mc,$router);
        }
    }
    return $arr;
}
/**
 * @param $cer
 * @return string
 * 定义控制器名称
 */
function getCer($cer){
    $arr = [
        'welcome'=>'欢迎页面',
        'index'=>'后台主页',
        'admin'=>'管理员',
        'menu'=>'后台菜单',
        'node'=>'后台节点',
        'role'=>'角色权限',
        'weblog'=>'网站日志',
        'cache'=>'缓存',
        'system'=>'系统配置',
        'login'=>'缓存',
        'article'=>'文章',
        'attribute'=>'文章属性',
        'category'=>'文章分类',
        'banner'=>'广告横幅',
        'link'=>'友情链接',
        'album'=>'相册管理',
        'albumcategory'=>'相册分类',
        'classify'=>'导航分类',
        'navigation'=>'导航',
        'liar'=>'失信名单',
        'area'=>'行政区域',
        'position'=>'职位管理',
        'department'=>'部门管理',
        'feedback'=>'评论管理',
        'tags'=>'tag标签',
        'visit'=>'访客记录',
        'collection'=>'采集管理',
    ];
    $strCode = strtolower($cer);
    $strArr = array_change_key_case($arr,CASE_LOWER);
    if(array_key_exists($strCode,$strArr)){
        $name = $strArr[$strCode];
    }else{
        $name = '未定义';
    }
    return $name;
}
/**
 * @param $action
 * @return string
 * 定义方法名称
 */
function getAct($action){
    $arr = [
        'index'=>'列表',
        'list'=>'配置项',
        'datalist'=>'数据',
        'add'=>'添加',
        'edit'=>'编辑',
        'city'=>'行政区域',
        'editSave'=>'保存编辑',
        'saveat'=>'保存',
        'switchat'=>'状态开关',
        'del'=>'删除',
        'delall'=>'删除',
        'logout'=>'退出',
        'updatefield'=>'更新字段',
        'updatenode'=>'更新系统节点',
        'info'=>'用户资料',
        'saveinfo'=>'保存用户资料',
        'emptydata'=>'清空数据',
        'check'=>'登录验证',
        'batchsave'=>'批量保存',
        'addc'=>'添加采集',
        'edits'=>'编辑采集',
        'courl'=>'采集网址',
        'codata'=>'内容采集',
        'content'=>'内容发布',
        'conlist'=>'采集数据列表',
        'import'=>'采集url',
        'imdata'=>'采集内容',
        'saveAts'=>'采集单个',
        'importAll'=>'导入全部',
        'Allimport'=>'执行导入全部',
        'select'=>'导入选中数据',
        'selectImport'=>'执行导入选中',
    ];
    $strCode = strtolower($action);
    $strArr = array_change_key_case($arr,CASE_LOWER);
    if(array_key_exists($strCode,$strArr)){
        $name = $strArr[$strCode];
    }else{
        $name = '未定义';
    }
    return $name;
}
/**
 * @param $table
 * @param $data
 * @return array
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 更新字段
 */
function FieldUpdate($table,$data){
    $nar = Db::name($table)->where('id',$data['id'])->find();
    $field = $data['field'];
    if($nar){
        Db::name($table)->save(['id' => $data['id'], $field => $data['value']]);
        $msg = array('code'=>200,'msg'=>'更新成功！');
    }else{
        $msg = array('code'=>300,'msg'=>'更新失败！');
    }
    return $msg;
}
/**
 * @param $table
 * @param $field
 * @param $id
 * @param int $value
 * @return array
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 更新状态
 */
function SwitchUp($table,$field,$id,$value=0){
    $arr = Db::name($table)->where('id',$id)->find();
    if($arr){
        if($arr[$field] == 1){
            $val = $value;
        }else{
            $val = 1;
        }
        Db::name($table)->save(['id' => $id, $field => $val]);
        $msg = array('code'=>200,'msg'=>'状态已更新!');
    }else{
        $msg = array('code'=>300,'msg'=>'数据出错啦!');
    }
    return $msg;
}
/**
 * @param array $data  参数
 * @param string $key      密钥
 * @return string
 * 生成签名
 */
function signMd5($data, $key)
{
    ksort($data);
    $sign_str = '';
    foreach ($data as $pk => $pv) {
        $sign_str .= "{$pk}={$pv}&";
    }
    $sign_str .= "key={$key}";
    $sign = md5($sign_str);
    return $sign;
}
/**
 * @param int $type
 * @param bool $adv
 * @return mixed
 * 获取客户ip
 */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
/**
 * @param string $sql
 * @param int $limit
 * @param array $prefix
 * @return array|false|string|string[]
 * 处理 sql 文件
 */
function mac_parse_sql($sql='',$limit=0,$prefix=[])
{
    // 被替换的前缀
    $from = '';
    // 要替换的前缀
    $to = '';

    // 替换表前缀
    if (!empty($prefix)) {
        $to   = current($prefix);
        $from = current(array_flip($prefix));
    }

    if ($sql != '') {
        // 纯sql内容
        $pure_sql = [];

        // 多行注释标记
        $comment = false;

        // 按行分割，兼容多个平台
        $sql = str_replace(["\r\n", "\r"], "\n", $sql);
        $sql = explode("\n", trim($sql));
        $cnm = base64_decode('YeeJiOadg+aJgOaciW1hZ2ljYmxhY2vvvIzmupDnoIFodHRwczovL2dpdGh1Yi5jb20vbWFnaWNibGFjaw==');
        // 循环处理每一行
        foreach ($sql as $key => $line) {
            // 跳过空行
            if ($line == '') {
                continue;
            }

            // 跳过以#或者--开头的单行注释
            if (preg_match("/^(#|--)/", $line)) {
                continue;
            }

            // 跳过以/**/包裹起来的单行注释
            if (preg_match("/^\/\*(.*?)\*\//", $line)) {
                continue;
            }

            // 多行注释开始
            if (substr($line, 0, 2) == '/*') {
                $comment = true;
                continue;
            }

            // 多行注释结束
            if (substr($line, -2) == '*/') {
                $comment = false;
                continue;
            }

            // 多行注释没有结束，继续跳过
            if ($comment) {
                continue;
            }

            // 替换表前缀
            if ($from != '') {
                $line = str_replace('`'.$from, '`'.$to, $line);
            }
            if ($line == 'BEGIN;' || $line =='COMMIT;') {
                continue;
            }
            // sql语句
            array_push($pure_sql, $line);
        }

        // 只返回一条语句
        if ($limit == 1) {
            return implode("",$pure_sql);
        }

        // 以数组形式返回sql语句
        $pure_sql = implode("\n",$pure_sql);
        $pure_sql = explode(";\n", $pure_sql);
        return $pure_sql;
    } else {
        return $limit == 1 ? '' : [];
    }
}
/**
 * @param string $url       远程地址
 * @param array $param      需要提交的参数
 * @param string $filename  上传文件名
 * @param int $wait         等待响应时间
 * @param string $method         等待响应时间
 * @return array|bool
 * 请求-提交数据  使用post方式提交
 */
function FCurl_post($url, $param = array(), $filename = '', $wait = 30,$method = 'POST')
{
    if (!is_array($param)) {
        return false;
    }

    if($filename){
        if($_FILES[$filename]['name']){
            $param['file'] = new \CURLFile($_FILES[$filename]['tmp_name'], $_FILES[$filename]['type'], $_FILES[$filename]['name']);
        }
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => $wait,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => $param, // 直接将参数数组作为POST数据
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
        ),
    ));

    if (strpos($url, "https") === 0) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    //echo $curl;
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);

    curl_close($curl);

    $result = array(
        'response_code' => $httpCode,
        'output' => $response,
    );
    return $result;
}
/**
 * @param string $url   要需下载的文件地址
 * @param string $save_dir  保存目录
 * @param string $filename  保存文件名
 * @param int $type   下载类型
 * @return array|bool
 * 下载远程文件
 */
function DownloadFile($url, $save_dir = '', $filename = '', $type = 0) {
    $ext = array('gif','jpg','jpeg','bmp','png','webp');
    if (trim($url) == '') {
        return false;
    }
    if (trim($save_dir) == '') {
        $save_dir = './';
    }
    if (0 !== strrpos($save_dir, '/')) {
        $save_dir.= '/';
    }
    //创建保存目录
    if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
        return false;
    }
    //获取远程文件所采用的方法
    if ($type) {
        $ch = curl_init();
        $timeout = 100;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // 跟随重定向
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 禁用SSL证书验证
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $content = curl_exec($ch);
        curl_close($ch);
    } else {
        ob_start();
        readfile($url);
        $content = ob_get_contents();
        ob_end_clean();
    }
    $size = strlen($content);
    //文件大小
    $info = pathinfo($url);
    $weurl = parse_url($url);
    if(isset($weurl['host']) && $weurl['host'] == 'mmbiz.qpic.cn'){
        if(isset($weurl['query'])){
            $exp = explode('=',$weurl['query']);
        }else{
            $exp = ['wx','jpg'];
        }
        $info['extension'] = $exp[1];
        $info['basename'] = 'WeChat_'.time().'.'.$info['extension'];
    }
    if(isset($info['extension']) && in_array($info['extension'],$ext)){
        if($filename){
            $fiex = '.'.$info['extension'];
            $fp2 = @fopen($save_dir . $filename.$fiex, 'a');
            $newname = $filename.$fiex;
        }else{
            $fp2 = @fopen($save_dir . $info['basename'], 'a');
            $newname = $info['basename'];
        }
        fwrite($fp2, $content);
        fclose($fp2);
        unset($content, $url);
        return array(
            'file_name' => $newname,
            'save_path' => '/'.$save_dir . $newname
        );
    }else{
        return array(
            'file_name' => $url,
            'save_path' => $url
        );
    }
}
/**
 * @param $data
 * @param $url
 * 节点权限验证
 */
function AuthNode($data,$url){
    //免签验证
    $skip = [
        'admin/',
        'admin/welcome',
    ];
    if($data){
        $roleId = implode(',',json_decode($data,true));
        $role = AllTable('role',[['status','=',1],['id','in',$roleId]]);
        $authId = [];
        foreach ($role as $v){
            if($v['auth']){
                $json = json_decode($v['auth']);
                foreach($json as $k=>$item){
                    array_push($authId,$item);
                }
            }
        }
        $aid = implode(',',$authId);
        $auth = AllTable('node',[['isOpen','=',1],['id','in',$aid]]);
        $node = [];
        foreach ($auth as $val){
            array_push($node,$val['nodeUrl']);
        }
        if(!in_array($url,$skip)){
            if(!in_array($url,$node)){
                $msg = ['code'=>300,'msg'=>'权限不足，请联系管理员!!'];
                echo json_encode($msg,JSON_UNESCAPED_UNICODE);
                die;
            }
        }
    }else{
        $msg = ['code'=>300,'msg'=>'权限不足，请联系管理员!'];
        echo json_encode($msg,JSON_UNESCAPED_UNICODE);
        die;
    }
}
/**
 * @param string $src  源文件地址
 * @param int $percent 图片质量
 * GD库 图片压缩
 */
function ImgCompress($src,$percent=1){
    //配置内存大小
    ini_set('memory_limit', '256M');
    //typearr存的是一些图片的格式，用作下文getimagesize()获取图片信息中的图片格式进行比对
    $typearr = array("gif", "jpeg", "png", "swf", "psd", "bmp",'17'=>'webp');
    list($width, $height, $type, $attr) = getimagesize($src);		//获取图片信息
    /**-----------------------------------------------------------------------------------------------------*/
    //对imagecreatefrom 系列函数进行拼接从文件或 URL 载入一幅图像，成功返回图像资源，失败则返回一个空字符串

    $func = "imagecreatefrom" . $typearr[$type - 1];
    $image = @$func($src);
    /**-----------------------------------------------------------------------------------------------------*/
    //创建一个新的真彩色图像
    $new_width = intval($width * $percent);
    $new_height = intval($height * $percent);
    $image_thump = imagecreatetruecolor($new_width, $new_height);
    /**-----------------------------------------------------------------------------------------------------*/
    // 处理透明背景图片变成黑色的问题
    if(strtolower($typearr[$type - 1])=='png'){
        imageantialias($image_thump, true);
        $color = imagecolorallocate($image_thump, 255, 255, 255);
        imagecolortransparent($image_thump, $color);
        imagefill($image_thump, 0, 0, $color);
    }
    /**----------------------------------------------------------------------------------------------------*/
    //图像处理
    imagecopyresampled($image_thump, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    /**--------------------------------------------------------------------------------------------------------------------*/
    //拼接一个image系列函数（将图片输出到浏览器或文件）
    $imgfunc= "image" . $typearr[$type - 1];
    //原图覆盖
    $imgfunc($image_thump,$src);
    //销毁图像资源
    imagedestroy($image);
    //销毁给定的变量
    unset($image);
}


/**
 *
 *  $src = "hyy.jpg";
 *  $dir
 *  $newwid = 640;
 *  $newhei = 480;
 */
/**
 * @param string $src
 * @param string $saveDir
 * @param int $newWid
 * @param int $newHei
 * GD库 压缩图片 缩略图
 */
function imgZip($src,$saveDir='', $newWid=350, $newHei=350)
{
    if($saveDir){
        $saveDir = 'uploads/'.$saveDir.'/'.date('Ym').'/';
        if(!file_exists($saveDir)) {
            mkdir($saveDir,0777,true);
        }
    }else{
        $saveDir = 'uploads/thumb/'.date('Ym').'/';
        if(!file_exists($saveDir)) {
            mkdir($saveDir,0777,true);
        }
    }
    $imgInfo = getimagesize($src);

    $imgType = image_type_to_extension($imgInfo[2], false);

    $fun = "imagecreatefrom{$imgType}";
    //声明图片 打开图片 在内存中
    $image = $fun($src);
    // 获取原始图片的宽高
    $sourceWidth = imagesx($image);
    $sourceHeight = imagesy($image);
    // 计算缩放比例
    $scale = min($newWid / $sourceWidth, $newHei / $sourceHeight);
    // 计算缩略图的宽高
    $thumbnailWidth = $sourceWidth * $scale;
    $thumbnailHeight = $sourceHeight * $scale;
    //在内存中建立一张图片
    $images2 = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight); //建立一个500*320的图片
    /**-----------------------------------------------------------------------------------------------------*/
    // 处理透明背景图片变成黑色的问题
    if(strtolower($imgType)=='png'){
        imageantialias($images2, true);
        $color = imagecolorallocate($images2, 255, 255, 255);
        imagecolortransparent($images2, $color);
        imagefill($images2, 0, 0, $color);
    }
    /**----------------------------------------------------------------------------------------------------*/
    //将原图复制到新建图片中
    //imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)

    imagecopyresampled($images2, $image, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $imgInfo[0], $imgInfo[1]);
    //销毁原始图片
    imagedestroy($image);
    //直接输出图片文件
    $imgfunc = 'image' . $imgType;
    /*header("Content-type: " . $imgInfo['mime']);
    $imgfunc($images2);*/
    //保存图片 到新文件
    $saveImg = $saveDir.date('Ymd').uniqid().'.'.$imgType;
    $imgfunc($images2,$saveImg ); //10代码输出图片的质量 0-100 100质量最高
    //销毁
    imagedestroy($images2);
    return $saveImg;
}

/**
 * @param $data
 * @param int $pid
 * @param string $pids
 * @param string $id
 * @param int $level
 * @return array
 * 递归返回所有上级菜单
 */
function upTree($data,$pid=0,$pids = 'pid',$id='id',$level=0){
    static $tree = array();
    foreach($data as $k=>$v){
        if($v[$id] == $pid){
            $v['lv'] = $level;
            $tree[] = $v;
            upTree($data,$v[$pids],$pids,$id,$level+1);
        }
    }
    return $tree;
}

/**
 * @param $table
 * @param int $pid
 * @param string $p_id
 * @param string $id
 * @param int $level
 * @return array
 * 返回分类下的所有子分类
 */
function ArrTree($table,$pid=0,$p_id = 'pid',$id = 'id',$level=0)
{
    $arr = Db::name($table)->select()->toArray();
    static $tree = array();
    foreach($arr as $v){
        if($v[$p_id] == $pid){
            $v['lv'] = $level;
            $tree[] = $v;
            ArrTree($table,$v[$id],$p_id,$id,$level+1);
        }
    }
    return $tree;
}

/**
 * @param string $sourcePath  源图地址
 * @param string $destinationPath 保存地址
 * @param int $quality 压缩比例 0-100
 * @throws ImagickException
 * Imagick 压缩方式
 */
function compressImage($sourcePath, $destinationPath, $quality) {
    $image = new Imagick($sourcePath);
    $image->setImageCompressionQuality($quality);
    $image->writeImage($destinationPath);
    $image->destroy();
}
/**
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 设置 api 导航菜单缓存
 */
function navApi(){
    $cate = GetMenu('category',[['isShow','=',1]],'id',['orderSort'=>'desc']);
    $nav = MdaTree($cate);
    SetCaChe('NavMenu',$nav);
}

/**
 * @param string $table
 * @param string $table2
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 上网导航缓存
 */
function Navigation($table='classify',$table2='navigation'){
    $data = AllTable($table,['status'=>1],['orderBy'=>'desc']);
    foreach ($data as $k=>$v){
        $data[$k]['nav'] = AllTables($table2,[['cid','=',$v['id']],['is_show','=',1]],$v['number'],['orderBy'=>'desc']);
    }
    SetCaChe('navigation',$data);
}
/**
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 获取区域
 */
function AreaList(){
    $city = GetMenu('area');
    SetCaChe('area',$city);
}

/**
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 更新配置文件
 */
function putFile(){
    $data = GetMenu('config');
    $arr = [];
    foreach($data as $v){
        $arr[$v['sys_variable']] = $v['sys_content'];
    }
    $path = config_path().'web.php';
    $str = "<?php \r\n".'return '.var_export($arr,true).';';
    file_put_contents($path,$str);
}

/**
 * @param $table
 * @param $data
 * 保存数据
 */
function SaveAt($table,$data){
    Db::name($table)->save($data);
}

/**
 * @param $table
 * @param $data
 * @return int|string
 * 保存数据并返回自增 id
 */
function saveId($table,$data){
    return Db::name($table)->insertGetId($data);
}

/**
 * @param $table
 * @param $data
 * $data = [
 *      ['foo' => 'bar', 'bar' => 'foo'],
 *      ['foo' => 'bar1', 'bar' => 'foo1'],
 *      ['foo' => 'bar2', 'bar' => 'foo2']
 *   ];
 * 添加多条数据
 */
function batchSave($table,$data){
    Db::name($table)->insertAll($data);
}

/**
 * @param $name
 * @param int $thumb  生成缩略图（0-1）
 * @param string $path  保存路径
 * @param int $newWid  生成缩略图宽度
 * @param int $newHei  生成缩略图高度
 * @return array
 * 图片上传
 */
function UploadImg($name,$thumb = 1,$path='',$newWid=350, $newHei=350){
    error_reporting(E_WARNING);
    //允许上传的文件类型
    $fileType = Cfg('img_type');
    $imgArr = explode(',',$fileType);
    //允许上传的文件大小
    $fileSize = (1024 * 1024) * Cfg('img_size');
    $saveName = [];
    //自定目录
    if($path){
        $paths = $path.'/'.date('Ym');
    }else{
        $paths = 'images/'.date('Ym');
    }
    if (isset($_FILES[$name]['name']) && is_array($_FILES[$name]['name'])) {
        if (!empty($_FILES[$name]['name'][0])) {
            // 获取上传文件
            $files = request()->file($name);
            foreach($files as $k=>$file){
                //获取文件后缀
                $ext = $file->extension();
                //判断文件上传类型
                if(!in_array($ext,$imgArr)){
                    $msg = [
                        'code'=>200,
                        'ident'=>1,
                        'msg'=>'上传的图片的类型不正确，允许的类型有：'.$fileType,
                        'result'=>'',
                    ];
                    return $msg;
                }
                //获取文件大小
                $Size = $file->getSize();
                //判断文件上传大小
                if($Size > $fileSize){
                    $msg = [
                        'code'=>200,
                        'ident'=>1,
                        'msg'=>'上传文件超过限制大小：'.$fileSize,
                        'result'=>'',
                    ];
                    return $msg;
                }
                $saveName[$k]['img'] = \think\facade\Filesystem::disk('public')->putFile( $paths, $file,'uniqid');
                $saveName[$k]['img'] = 'uploads/'.$saveName[$k]['img'];
                //获取文件存放路径
                $saveName[$k]['code'] = 200;
                $saveName[$k]['msg'] = '上传成功';
                //压缩图片
                ImgCompress($saveName[$k]['img'],1);
                //生成缩略图
                if($thumb){
                    $saveName[$k]['thumb'] = imgZip($saveName[$k]['img'],$path,$newWid,$newHei);
                }
            }
            $msg = [
                'code'=>200,
                'ident'=>2,
                'msg'=>'文件上传成功',
                'result'=>$saveName,
            ];
            return $msg;
        }else{
            $msg = [
                'code'=>300,
                'msg'=>'请选择图片上传[]！',
                'result'=>'',
            ];
            return $msg;
        }

    } else {
        if(isset($_FILES[$name]) && $_FILES[$name]['error'] === UPLOAD_ERR_OK) {
            // 获取上传文件
            $files = request()->file($name);
            $saveName['img'] = \think\facade\Filesystem::disk('public')->putFile( $paths, $files,'uniqid');
            $saveName['img'] = 'uploads/'.$saveName['img'];
            //压缩图片
            ImgCompress($saveName['img'],1);
            //生成缩略图
            if($thumb){
                $saveName['thumb'] = imgZip($saveName['img'],$path,$newWid,$newHei);
            }
            $msg = [
                'code'=>200,
                'ident'=>2,
                'msg'=>'文件上传成功',
                'result'=>$saveName,
            ];
            return $msg;
        }else{
            $msg = [
                'code'=>300,
                'msg'=>'请选择图片上传！',
                'result'=>'',
            ];
            return $msg;
        }
    }
}

/**
 * @param $name
 * @return array
 * 上传文件
 */
function uploadFile($name){
    if(isset($_FILES[$name]) && $_FILES[$name]['error'] === UPLOAD_ERR_OK) {
        // 获取上传文件
        $files = request()->file($name);
        //获取文件后缀
        $ext = $files->extension();
        $fileType = Cfg('file_type');
        $imgArr = explode(',',$fileType);
        //判断文件上传类型
        if(!in_array($ext,$imgArr)){
            $msg = [
                'code'=>200,
                'ident'=>1,
                'msg'=>'上传的图片的类型不正确，允许的类型有：'.$fileType,
                'result'=>'',
            ];
            return $msg;
        }
        //获取文件大小
        $Size = $files->getSize();
        $fileSize = (1024 * 1024) * Cfg('file_size');
        //判断文件上传大小
        if($Size > $fileSize){
            $msg = [
                'code'=>200,
                'ident'=>1,
                'msg'=>'上传文件超过限制大小：'.$fileSize,
                'result'=>'',
            ];
            return $msg;
        }
        $saveName = [];
        if(is_array($files)){
            foreach($files as $k=>$file){
                $saveName[$k]['file'] = \think\facade\Filesystem::disk('public')->putFile( 'uploadFile', $file,'uniqid');
                $saveName[$k]['file'] = 'uploads/'.$saveName[$k]['img'];
                //获取文件存放路径
                $saveName[$k]['code'] = 200;
                $saveName[$k]['msg'] = '上传成功';
            }
        }else{
            $saveName['file'] = \think\facade\Filesystem::disk('public')->putFile( 'uploadFile', $files,'uniqid');
            $saveName['file'] = 'uploads/'.$saveName['file'];
        }
        $msg = [
            'code'=>200,
            'ident'=>2,
            'msg'=>'文件上传成功',
            'result'=>$saveName,
        ];
        return $msg;
    } else {
        // 文件上传失败或未上传
        $msg = [
            'code'=>300,
            'msg'=>'请选择文件上传！',
            'result'=>'',
        ];
        return $msg;
    }
}
//打开缓冲
function open_buffer(){
    set_time_limit(0);  //设置程序执行时间
    ignore_user_abort(true);    //设置断开连接继续执行
    ob_end_clean();//清空缓存
    ob_start(); //打开输出缓冲控制
}
//输出缓冲
function output_buffer(){
    //echo ob_get_clean();    //用于获取缓冲区的内容，并清空缓冲区。
    ob_flush();   //用于刷新缓冲区，将内容立即发送到浏览器。
    flush();   //刷新缓冲区的内容，输出
}
//进度条
function ProgressBar(){
    $show = '<div style="padding: 8px; background: #fff; width: %upx"><div style="padding: 0;border-radius:20px; background:#eee; width: %upx"><div id="progress" style="padding: 0;border-radius:20px; background-color: #FFCC66; border: 0; width:0px; text-align:center; height:18px;line-height:18px;"></div></div><div id="msg" style="font-family: Tahoma; font-size: 9pt;">正在处理...</div><div id="percent" style="position:relative;top:-34px;text-align:center;font-weight:bold;font-size:8pt;height:18px;line-height:18px;">0%%</div></div>';
    echo $show;
}
//进度条数据实时加载
function replace_msg(){
    return $script = '<script>document.getElementById("percent").innerText="%u%%";document.getElementById("progress").style.width="%u%%";document.getElementById("msg").innerText="%s";</script>';
}
//开启统计程序执行时间
function openTime(){
    return microtime(true);
}
//显示程序执行时间
function showTime($time,$flo=3){
    $end = microtime(true);
    return  round($end-$time,$flo);
}
/**
 * @param string $url   url列表地址
 * @param string $s     开始匹配
 * @param string $e     结束匹配
 * @return array|bool|false|string
 * 返回列表下的文章链接网址
 */
function get_url($url,$s,$e){
    //获取列表页
    $list = array();
    if($url){
        //获取列表页
        $str = strpos($url,'(*)');
        if($str){
            for($i=$s;$i<=$e;$i++){
                $list[] = str_replace('(*)',$i,$url);
            }
            return $list;
        }else{
            return  @file_get_contents($url);
        }
    }else{
        return false;
    }
}
/**
 * @param string $html    url地址
 * @param string $start   开始分割的边界
 * @param string $end     结束分割的边界
 * @return bool|string    返回内容结果
 * 返回匹配到的内容
 */
function cut_html($html, $start, $end) {
    if (empty($html)) return false;
    if(is_array($html)){
        $list = array();
        foreach ($html as $k=>$v){
            $list[] = $v;
        }
        $arr = array();
        foreach ($list as $kk=>$vv){
            $html = str_replace(array("\r", "\n"), "",file_get_contents($vv));
            $start = str_replace(array("\r", "\n"), "", $start);
            $end = str_replace(array("\r", "\n"), "", $end);
            $html = explode(trim($start), $html);
            if(is_array($html) && isset($html[1])){
                $arr[] = explode(trim($end), $html[1]);
            }else{
                $arr[] = array('');
            }
        }
        $str = '';
        foreach ($arr as $ky=>$vy){
            $str .= ($vy[0]);
        }
        return $str;
    }else{
        $html = str_replace(array("\r", "\n"), "", file_get_contents($html));
        $start = str_replace(array("\r", "\n"), "", $start);
        $end = str_replace(array("\r", "\n"), "", $end);
        $html = explode(trim($start), $html);
        if(is_array($html) && isset($html[1])){
            $html = explode(trim($end), $html[1]);
        }else{
            $html = array('');
        }
        return trim($html[0]);
    }
}
/**
 * @param string $page_html  要匹配的字符串内容
 * @return array
 * 返回匹配出的网址
 */
function get_list($page_html){
    if($page_html){
        if(is_array($page_html)){
            $arr = array();
            foreach ($page_html as $k=>$v){
                preg_match_all('/<a [^>]*href=[\'"]?([^>\'" ]*)[\'"]?[^>]*>([^<\/]*)<\/a>/Ssi', $v, $out);
                array_push($arr,$out);
            }
            return $arr;
        }else{
            preg_match_all('/<a [^>]*href=[\'"]?([^>\'" ]*)[\'"]?[^>]*>([^<\/]*)<\/a>/Ssi', $page_html, $out);
            return $out;
        }
    }
}
/**
 * @param $html
 * @return array|false|string[]
 * 返回匹配规则
 */
function replace_sg($html) {
    $list = explode('[内容]', $html);
    if (is_array($list)) foreach ($list as $k=>$v) {
        $list[$k] = str_replace(array("\r", "\n"), '', trim($v));
    }
    return $list;
}
/**
 * @param string $path  传入的路径(用于判断是否为远程地址)
 * @param string $url   远程地址，例：http://www.demo.com/lkf/kdi 或者：http://www.demo.com
 * @return string
 * 返回绝对地址路径 例：htt://www.demo.com/img/test.jpg
 */
function fileUrl($path,$url){
    $urls = parse_url($path);
    if(!isset($urls['scheme']) && !isset($urls['host'])){
        $nurl = parse_url($url);
        $imgUrl = $nurl['scheme'].'://'.$nurl['host'].$path;
    }else{
        $imgUrl = $path;
    }
    return $imgUrl;
}
/**
 * @param string $text  文本内容
 * @return mixed
 * 返回匹配的图片地址
 */
function getImg($text){
    $url = parse_url($text);
    if(isset($url['scheme']) && isset($url['host'])){
        $page_html = file_get_contents($text);
    }else{
        $page_html = $text;
    }
    preg_match_all('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/Ssi', $page_html, $out);
    return $out;
}

/**
 * @return string
 * 获得访客浏览器类型
 */
function GetBrowser()
{
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $br = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i', $br)) {
            $br = 'MSIE';
        } elseif (preg_match('/Firefox/i', $br)) {
            $br = 'Firefox';
        } elseif (preg_match('/Chrome/i', $br)) {
            $br = 'Chrome';
        } elseif (preg_match('/Safari/i', $br)) {
            $br = 'Safari';
        } elseif (preg_match('/Opera/i', $br)) {
            $br = 'Opera';
        } else {
            $br = 'Other';
        }
        return "浏览器为：" . $br;
    } else {
        return "获取浏览器信息失败！";
    }
}
/**
 * @return string
 * 获得访客浏览器语言
 */
function GetLang()
{
    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $lang = substr($lang, 0, 5);
        if (preg_match("/zh-cn/i", $lang)) {
            $lang = "简体中文";
        } elseif (preg_match("/zh/i", $lang)) {
            $lang = "繁体中文";
        } else {
            $lang = "English";
        }
        return "浏览器语言为：" . $lang;
    } else {
        return "获取浏览器语言失败！";
    }
}
/**
 * @return string
 * 获取客户端操作系统信息包括win10
 * 获取访问设备操作系统
 */
function GetOs(){
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $os = '未知操作系统';

    if (preg_match('/win/i', $agent) && stripos($agent, '95'))
    {
        $os = 'Windows 95';
    }
    if (preg_match('/win 9x/i', $agent) && stripos($agent, '4.90'))
    {
        $os = 'Windows ME';
    }
    if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent))
    {
        $os = 'Windows 98';
    }
    if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent))
    {
        $os = 'Windows NT';
    }
    if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent))
    {
        $os = 'Windows Vista';
    }
    if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent))
    {
        $os = 'Windows 7';
    }
    if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent))
    {
        $os = 'Windows 8';
    }
    if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent))
    {
        $os = 'Windows 10';#添加win10判断
    }
    if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent))
    {
        $os = 'Windows XP';
    }
    if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent))
    {
        $os = 'Windows 2000';
    }
    if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent))
    {
        $os = 'Windows 32';
    }
    if (stripos($agent,'linux'))
    {
        $os = 'Linux';
    }
    if (stripos($agent,'unix'))
    {
        $os = 'Unix';
    }
    if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent))
    {
        $os = 'SunOS';
    }
    if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent))
    {
        $os = 'IBM OS/2';
    }
    if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent))
    {
        $os = 'Macintosh';
    }
    if (stripos($agent,'PowerPC'))
    {
        $os = 'PowerPC';
    }
    if (stripos($agent,'AIX'))
    {
        $os = 'AIX';
    }
    if (stripos($agent,'HPUX'))
    {
        $os = 'HPUX';
    }
    if (stripos($agent,'NetBSD'))
    {
        $os = 'NetBSD';
    }
    if (stripos($agent,'BSD'))
    {
        $os = 'BSD';
    }
    if (stripos($agent,'OSF1'))
    {
        $os = 'OSF1';
    }
    if (stripos($agent,'IRIX'))
    {
        $os = 'IRIX';
    }
    if (stripos($agent,'FreeBSD'))
    {
        $os = 'FreeBSD';
    }
    if (stripos($agent,'teleport'))
    {
        $os = 'teleport';
    }
    if (stripos($agent,'flashget'))
    {
        $os = 'flashget';
    }
    if (stripos($agent,'webzip'))
    {
        $os = 'webzip';
    }
    if (stripos($agent,'offline'))
    {
        $os = 'offline';
    }
    if(stripos($agent, 'iphone')){
        $os = 'iphone';
    }
    if(stripos($agent, 'ipad')){
        $os = 'ipad';
    }
    if(stripos($agent, 'android')){
        $os = 'android';
    }
    if (stripos($agent, "SAMSUNG") || stripos($agent, "Galaxy") || stripos($agent, "GT-") || stripos($agent, "SCH-") || stripos($agent, "SM-")) {
        $os = 'android ->三星';
    }
    if (stripos($agent, "Huawei") || stripos($agent, "Honor") || stripos($agent, "H60-") || stripos($agent, "H30-")) {
        $os = 'android ->华为';
    }
    if (stripos($agent, "Lenovo")) {
        $os = 'android ->联想';
    }
    if (stripos($agent, "MI-ONE") || stripos($agent, "MI 1S") || stripos($agent, "MI 2") || stripos($agent, "MI 3") || stripos($agent, "MI 4") || stripos($agent, "MI-4") || stripos($agent,"xiaomi"))  {
        $os = 'android ->小米';
    }
    if (stripos($agent, "HM NOTE") || stripos($agent, "HM201")) {
        $os = 'android ->红米';
    }
    if (stripos($agent, "Coolpad") || stripos($agent, "8190Q") || stripos($agent, "5910")) {
        $os = 'android ->酷派';
    }
    if (stripos($agent, "ZTE") || stripos($agent, "X9180") || stripos($agent, "N9180") || stripos($agent, "U9180")) {
        $os = 'android ->中兴';
    }
    if (stripos($agent, "OPPO") || stripos($agent, "X9007") || stripos($agent, "X907") || stripos($agent, "X909") || stripos($agent, "R831S") || stripos($agent, "R827T") || stripos($agent, "R821T") || stripos($agent, "R811") || stripos($agent, "R2017")) {
        $os = 'android ->OPPO';
    }
    if (stripos($agent, "HTC") || stripos($agent, "Desire")) {
        $os = 'android ->HTC';
    }
    if (stripos($agent, "vivo")) {
        $os = 'android ->vivo';
    }
    if (stripos($agent, "K-Touch")) {
        $os = 'android ->天语';
    }
    if (stripos($agent, "Nubia") || stripos($agent, "NX50") || stripos($agent, "NX40")) {
        $os = 'android ->努比亚';
    }
    if (stripos($agent, "M045") || stripos($agent, "M032") || stripos($agent, "M355")) {
        $os = 'android ->魅族';
    }
    if (stripos($agent, "DOOV")) {
        $os = 'android ->朵唯';
    }
    if (stripos($agent, "GFIVE")) {
        $os = 'android ->基伍';
    }
    if (stripos($agent, "Gionee") || stripos($agent, "GN")) {
        $os = 'android ->金立';
    }
    if (stripos($agent, "HS-U") || stripos($agent, "HS-E")) {
        $os = 'android ->海信';
    }
    if (stripos($agent, "Nokia")) {
        $os = 'android ->诺基亚';
    }
    return $os;
}