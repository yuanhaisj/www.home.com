<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\log;
class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function register()
    {
        //
        return $this->fetch();
    }
    
    /**
     * @param  phone  string
     * @return json
     */
    public function sendSms(Request $request)
    {
        //接收参数
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
                'msg' =>'参数不全'
            ];

            return json($return);
        }
        
        //随机生成短信验证码
        $smsCode=rand(1,100000);
        
        //验证码存入redis中
        $redis=new \Redis();
        $redis->connect('127.0.0.1',6379);
        $redis->setex($params['phone']."_code",1800,$smsCode);

        log::info($params['phone']."的注册验证码是".$smsCode);

        return json($return);

        // session_start();
        // $_SESSION[$params['phone']."_code"]=$smsCode;
        $smsConf=[
            'key'      =>'93981ecaa9196d8eaf53a75296a21a30', //申请的APPKEY
            'mobile'   =>$params['phone'], //接收短信的用户手机号码
            'tpl_id'   =>'137166', //申请的短信模板ID，根据实际情况修改
            'tpl_value'=>'#code#='.$smsCode //设置的模板变量，根据实际情况修改
        ];

        $sendUrl='http://v.juhe.cn/sms/send'; //短信接口的url

        $return = $this->HttpCurl($sendUrl,true,$smsConf);

        return json($return);
    }
    
    /**
     * 完成注册
     * 
     */
    public function doRegister(Request $request)
    {
        $params=$request->param();

        //接口返回格式
        $return=[
            'code'=>2000,
            'msg' =>'成功',
            'data'=>[]
        ];

        // session_start();
        // $code =  $_SESSION[$params['phone']."_code"];

        //从redis中获取短信验证码
        $redis=new \Redis();
        $redis->connect('127.0.0.1',6379);

        $code=$redis->get($params['phone']."_code");

        log::info($params['phone'].'用户输入短信验证码是：'.$params['sms_code']."redis缓存存储验证码是".$code);

        if(empty($code) || $code !=$params['sms_code']){
            $return=[
                'code'=>4007,
                'msg' =>'短信验证码输入错误',
                'data'=>[]
            ];
            return json($return);
        }

        
        // 删除缓存存储的短信验证码
        $redis->del($params['phone']."_code");

        Db::query('insert into bp_user (username,phone,email,password) values("",?,"",?)',[$params['phone'],md5($params['password'])]);

        return json($return);
    }

    /**
     * 用户登录页面
     */
    public function login()
    {
        return $this->fetch();
    }
}
