<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
class Lists extends Controller
{
    public function lists(){
        return $this->fetch();
    }

    /**
     * 获取用户列表的接口
     * @param  $limit int
     * @param  $sign string
     * @return json
     */
    public function userList(Request $request)
    {
        //接受传递过来的参数
        $params=$request->param();
        
        //定义返回的格式
        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];

        if(!isset($params['limit']) || empty($params['limit'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不能为空'
            ];

            return json($return);
        }

        if(!isset($params['sign']) || empty($params['sign'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不能为空'
            ];

            return json($return);
        }
        
        //效验用户的签名
        $result=$this->checkUserSign($params);

        if($result['code']!=2000){
            return json($result);
        }

        $userList=Db::query('select * from ks_user limit ?',[$params['limit']]);
    

        $return['data']=$userList;

        return json($return);
    }
    
    /**
     * 删除用户接口
     * @param  id  int  用户的id
     * @param  sign  string
     * @return json
     */
    public function delUser(Request $request)
    {
        //接受传递的所有参数
        $params=$request->param();

        //定义返回的格式
        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];

        if(!isset($params['id']) || empty($params['id'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不能为空'
            ];

            return json($return);
        }
        
        //效验sign签名
        $result=$this->checkUserSign($params);

        if($result['code'] !=2000){
            return json($result);
        }

        try{

            //执行删除的操作
            Db::query('delete from ks_user where id=?',[$params['id']]);

        }catch(\Exception $e){

            $return=[
                'code'=>$e->getCode(),
                'msg' =>$e->getMessage()
            ];
            
            return json($return);
        }
        

        return json($return);
    }

    /**
     * 效验用户sign签名加密
     * @param  $params
     * @return array
     */
    public function checkUserSign($params)
    {
        //sign 加密的秘钥
        $sercet="123456";

        //定义返回的格式
        $return=[
            'code'=>2000,
            'msg' =>'成功'
        ];

        if(!isset($params['sign']) || empty($params['sign'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不能为空'
            ];

            return $return;
        }

        $sign=$params['sign'];

        unset($params['sign']);
        
        //生成自己的签名
        $string=http_build_query($params);
        $self_sign=md5($string.$sercet);
        // echo $self_sign;exit;
        //比较post传递的sign和自己生成签名是否一致
        
        if($sign!=$self_sign){
            //定义返回的格式
            $return=[
                'code'=>4006,
                'msg' =>'sign签名不合法',
            ];

            return $return;
        }

        return $return;
    }

}
