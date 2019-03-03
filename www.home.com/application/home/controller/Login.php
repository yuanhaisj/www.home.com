<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Db;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //登录
        return $this->fetch();
    }

    /**
     * 登录
     * @param  shopname  string
     * @param  password  string
     * @return json
     */
    public function login(Request $request)
    {
        //接受所有的参数
        $params=$request->param();

        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];

        if(!isset($params['shopname']) || empty($params['shopname'])){
            $return=[
                'code'=>4001,
                'msg' =>'商店名称不能为空'
            ];
            return json($return);
        }

        if(!isset($params['password']) || empty($params['password'])){
            $return=[
                'code'=>4002,
                'msg' =>'密码不能为空',
            ];
            return json($retuen);
        }
        
        $shopname=$params['shopname'];
        $shop=Db::query("select * from kaoshi where shopname=?",[$shopname]);

        if(empty($shop)){
            $return=[
                'code'=>4003,
                'msg' =>'用户不存在'
            ];
            return json($return);
        }else{
            $password=md5($params['password']);
            if($shop[0]['password']!=$password){
                $return=[
                    'code'=>4004,
                    'msg' =>'密码不一致'
                ];
                return json($return);
            }

            $expried_at=date("Y-m-d H:i:s",time()+3600);
            Db::query('update kaoshi set token=replace(uuid(),"-",""), expried_at=? where shopname=?',[$expried_at,$shopname]);
            $shopInfo=Db::query('select token from kaoshi where shopname=?',[$shopname]);

            $return['data']['token']=$shopInfo[0]['token'];
            // var_dump($return['data']['token']);die;
            
        }
        return json($return);
    }

    /**
     * 验证token值
     * @param  token string
     * @return json
     */
    public function token(Request $request)
    {
        //接受所有的参数
        $params=$request->param();

        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];

        if(!isset($params['token']) || empty($params['token'])){
            $return=[
                'code'=>4005,
                'msg' =>'token不能为空'
            ];
            return json($return);
        }
        
        $token=$params['token'];
        $data=Db::query('select token,expired_at from kaoshi where token=?'[$token]);
        
        if(empty($data)){
            $return=[
                'code'=>4006,
                'msg' =>'token不合法'
            ];

            return json($return);
        }else{
            if($data[0]['expired_at']>time()){
                $return=[
                    'code'=>4007,
                    'msg' =>'token已过期'
                ];
                return json($retorn);
            }
        }
        return json($return);
    }

}
