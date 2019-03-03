<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //使用php去链接redis对象
        // $redis=new \Redis();
        // $redis->connect('127.0.0.1',6379);

        // var_dump($redis);die;
        
        $data=[
            'nav_num'=>3
        ];
        
       $nav = $this->HttpCurl('http://www.home.com/api/home/nav',true,$data);

       // var_dump($nav);die;
        return $this->fetch();
    }
    
    /**
     * desc 商品详情页面
     *
     * @return \think\Response
     */
    public function detail(Request $request)
    {
        $params=$request->param();

        $gid=isset($params['id']) ? $params['id']:0;

        //第一种方式curl请求api接口
        // $data=[
        //     'gid'=>$gid,
        // ];
        // $detail=$this->HttpCurl("http://www.home.com/api/products/detail",true,$data);
        // // var_dump($detail);die;
        // $this->assign($detail['data']);
        

        $this->assign('gid',$gid);
        return $this->fetch();
    }
}
