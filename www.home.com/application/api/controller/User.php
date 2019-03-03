<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;

class User extends Controller
{
    /**
     * 用户登录接口
     * @param phone
     * @param password
     * @return \think\Response
     */
    public function login(Request $request)
    {
        //接收所有的参数
        $params=$request->param();

        //接口返回格式
        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];

        //判断参数是否传递
        if(!isset($params['phone']) || empty($params['phone'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不全',
            ];

            return json($return);
        }

        //判断参数是否传递
        if(!isset($params['password']) || empty($params['password'])){
            $return=[
                'code'=>4001,
                'msg' =>'参数不全',
            ];

            return json($return);
        }

        //用户名
        $phone=$params['phone'];
        //密码
        $password=$params['password'];

        $user=Db::query('select * from bp_user where phone=?',[$phone]);

        if(empty($user)){
            $return=[
               'code'=>4005,
               'msg' =>'用户不存在'
            ];

            return json($return);
        }else{

            $db_password=$user[0]['password'];//数据库查到的密码
            $password=md5($password);
            
            //比较密码是否一致
            if($db_password !==$password){
                $return=[
                    'code'=>4006,
                    'msg' =>'密码不正确'
                ];

                return json($return);
            }

            $expired_at=date('Y-m-d H:i:s',time()+7200);
            //生成token值，存入到数据库中
            Db::query('update bp_user set access_token = replace(uuid(),"-","") , expired_at = ? where phone = ?',[ $expired_at, $phone ]);
            
            //查询数据库中token的值
            $user1=Db::query('select access_token from bp_user where phone=?',[$phone]);

            $return['data']['token']=$user1[0]['access_token'];
        }

        return json($return);
    }

    /**
     * 效验token值的接口
     * $token
     */
    public function checkToken(Request $request)
    {
        //接受所有的参数
        $params=$request->param();

        $return=$this->checkCommonToken($params);//效验token值


        return json($return);
    }
    
    /**
     * 通过token获取用户信息
     */
    public function getUserInfo(Request $request)
    {
        //接受所有对的参数
        $params=$request->param();

        //接口返回格式
        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];

        $check=$this->checkCommonToken($params);

        if($check['code'] !==2000){
            return json($check);
        }

        $user=Db::query('select * from bp_user where access_token =?',[$params['token']]);

        $return['data']=$user[0];

        return json($return);
    }
}
