<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;

class Products extends Controller
{
    /**
     * 商品详情接口
     *
     * @return \think\Response
     */
    public function detail(Request $request)
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
        if(!isset($params['gid']) || empty($params['gid'])){
            $return =[
                'code'=>4001,
                'msg' =>'参数不全'
            ];

            return json($return);
        }
        
        //gid接受参数
        $gid=$params['gid'];

        //获取商品的详情
        $goodsInfo=Db::query('select * from bp_goods where id=?',[$gid]);

        $goodsImg=Db::query('select image_url,source_image_url from bp_goods_img where goods_id =?',[$gid]);

        $return['data']['goods']=$goodsInfo[0];

        $return['data']['goods_image']=$goodsImg;

        return json($return);
    }
    
    /**
     * 商品评论接口
     * @param  goods_id  int
     * @param  c_type  int
     * @param  content string
     * @return json
     */
    public function comment(Request $request)
    {
        //接受请求的参数
        $params=$request->param();
        // var_dump($params);die;

        //接口返回格式
        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];
        
        //验证sign签名是否合法
        $result=$this->createSign($params);

        if($result['code'] !=2000){
            return json($result);
        }
        
        //判断参数是否传递
        if(!isset($params['goods_id']) || empty($params['goods_id'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不全'
            ];
            return json($return);
        }

        if(!isset($params['c_type']) || empty($params['c_type'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不全'
            ];
            return json($return);
        }

        if(!isset($params['content']) || empty($params['content'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不全'
            ];
            return json($return);
        }
        
        //try捕获异常
        try{
        //插入评论的sql
        Db::query('insert into bp_goods_comment (goods_id,comment_type,content) values(?,?,?)',[$params['goods_id'],$params['c_type'],$params['content']]);
    }catch(\Exception $e){
        $return=[
            'code'=>$e->getCode(),
            'msg' =>$e->getMessage()
        ];
    }
        
        return json($return);
    }
}
