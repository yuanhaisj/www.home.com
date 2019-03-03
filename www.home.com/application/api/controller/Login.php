<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    /**
     * 考试后台用户登录接口
     *
     * @return \think\Response
     */
    public function login(Request $request)
    {
        //接受传递的所有参数
        $params=$request->param();

        //定义返回的格式
        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];
        
        //判断用户名是否传递，或者用户名是否为空
        if(!isset($params['username']) || empty($params['username'])){
            $return=[
                'code'=>4001,
                'msg' =>'用户名不能为空',
            ];

            return json($return);
        }
        
        //判断密码是否传递，或者密码是否为空
        if(!isset($params['password']) || empty($params['password'])){
            $return=[
                'code'=>4001,
                'msg' =>'密码不能为空',
            ];

            return json($return);
        }

        $username=$params['username'];
        
        //使用用户名去数据库中查询数据
        $user=Db::query('select * from ks_admin_user where username=?',[$username]);
        
        //用户不存在
        if(empty($user)){
            $return=[
              'code'=>4002,
              'msg' =>'用户不存在'
            ];

            return json($return);
        }else{

            //用户传递的密码
            $postPwd=md5($params['password']);

            //数据库查询出来的密码
            $dbPwd=$user[0]['password'];
            
            //密码不一致
            if($postPwd != $dbPwd){
                $return=[
                    'code'=>4003,
                    'msg' =>'用户密码错误',
                ];

                return json($return);
            }
            
            //过期时间
            $expired_at=date('Y-m-d H:i:s',time()+3600);

            //更新用户的token及token过期时间
            Db::query('update ks_admin_user set token =replace(uuid(),"-",""),expired_at=? where username=?',[$expired_at,$username]);

            $userInfo=Db::query('select token from ks_admin_user where username=?',[$username]);

            $return['data']['token']=$userInfo[0]['token'];
        }

        return json($return);
    }

    //验证token的接口是否过期
    
    public function checkUserToken(Request $request)
    {
        //接受传过来的参数
        $params=$request->param();

        //定义返回的格式
        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[] 
        ];

        if(!isset($params['token']) || empty($params['token'])){
            $return=[
                'code'=>4004,
                'msg' =>'token不能为空'
            ];

            return json($return);
        }
        
        $token=$params['token'];

        $data=Db::query('select token,expired_at from ks_admin_user where token=?',[$token]);

        //token不存在
        if(empty($data)){
            $return=[
                'code'=>4004,
                'msg' =>'token不合法'
            ];

            return json($return);
        }else{

            $expired_at=$data[0]['expired_at'];
            
            if(date('Y-m-d H:i:s',time())>$expired_at){
                $return=[
                    'code'=>4005,
                    'msg' =>'token已过期'
                ];

                return json($return);
            }
        }

        return json($return);
    }
    
}
