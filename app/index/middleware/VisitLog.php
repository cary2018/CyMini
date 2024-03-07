<?php
declare (strict_types = 1);

namespace app\index\middleware;

class VisitLog
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $ip = new \Ip2Region();
        $region = $ip->btreeSearch(get_client_ip());
        if(!$region){
            $region = '';
        }
        if(isset($_SERVER['HTTP_REFERER'])){
            $from = $_SERVER['HTTP_REFERER'];
        }else{
            $from = '';
        }
        $data = array(
            'ip'=>get_client_ip(),
            'from_url'=>$from,
            'to_url'=>$url,
            'region' => $region['region'],
            'clientInfo'=>'操作系统：'.GetOs().'<br>'.GetBrowser().'<br>'.GetLang(),
            'createTime'=>time()
        );
        SaveAt('visit',$data);
        //前置中间件
        return $next($request);
    }
}
