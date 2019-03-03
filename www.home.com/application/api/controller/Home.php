<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
class Home extends Controller
{
    /**
     * @desc 首页导航栏的接口
     * @param nav_num int 
     * @return \think\Response
     */
    public function nav(Request $request)
    {
        //接受请求的参数
        $params=$request->param();

        //生成签名函数
        $result=$this->createSign($params);
        if($result['code'] !=2000){
            return json($result);
        }

        //接口返回格式
        $return =[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];

        if(!isset($params['nav_num']) || empty($params['nav_num'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不全'
            ];
            return json($return);
        }
        //限制导航输出的条数
        $num=$params['nav_num'];
        //查询导航栏的数据
        $nav_list=Db::query('select id,name,url from bp_nav limit ?',[$num]);

        $return['data']=$nav_list;

        return json($return);
    }

    /**
     * @desc 首页banner的接口
     * @param b_num int
     * @return \think\Response
     */
    public function banner(Request $request)
    {
        //接受请求的参数
        $params=$request->param();

        //接口返回格式
        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];

        //判断参数是否传递
        if(!isset($params['b_num']) || empty($params['b_num'])){
            $return =[
                'code'=>4001,
                'msg' =>'参数不全'
            ];

            return json($return);
        }

        //限制banner输出的条数
        $num=$params['b_num'];

        //查询banner图的数据2                                                           
        
        $banner_list=Db::query('select name,image_url,url from bp_ad where cate_id=1 limit ?',[$num]);

        $return['data']=$banner_list;

        return json($return); 

    }

    /**
     * @desc 首页商品列表的接口
     * @param g_num int  商品数量
     * @param tags  int  商品标识
     * @return \think\Response
     */
    public function goods(Request $request)
    {
        //接受请求的参数
        $params=$request->param();

        //生成签名函数
        $result=$this->createSign($params);
        if($result['code'] !=2000){
            return json($result);
        }

        //接口返回格式
        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];

        //判断参数是否传递
        if(!isset($params['g_num']) || empty($params['g_num'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不全'
            ];

            return json($return);
        }

        if(!isset($params['tags']) || empty($params['tags'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不全'
            ];

            return json($return);
        }

        $num=$params['g_num'];//限制数量
        $tags=$params['tags'];//类别

        $goods=Db::query('select id,goods_name,goods_image,shop_price,market_price,level from bp_goods where tags=? limit ?',[$tags,$num]);

        $return['data']=$goods;

        return json($return);
    }

}
