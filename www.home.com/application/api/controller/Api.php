<?php 
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Db;

class Api extends Controller
{
    public $sercet="123456qwerty";

	//测试加入一条信息
	public function add()
	{
		Db::query('insert into user(username,password) values(?,?)',['admin',md5('123')]);	
	}

    //生成签名
	public function createSign(Request $request)
	{
		$params=$request->param();
        
        //把传递的参数拼接到一起
		$string=http_build_query($params);//构造url字符串的方法
        // print_r($string);
		$sercet=$this->sercet;

		$sign=md5($string.$sercet);
		echo $sign;die;
	}
    
    //验证签名
    public function checkSign(Request $request)
    {
    	$params=$request->param();

    	// var_dump($params);

    	if(!isset($params['sign']) || empty($params['sign'])){
    		echo "sign签名不能为空";die;
    	}

    	$sign=$params['sign'];

    	unset($params['sign']);

    	$string=http_build_query($params);
    	// echo $string;die;
    	$sercet=$this->sercet;
    	$new_sign=md5($string.$sercet);//重新生成签名

    	if($new_sign !== $sign){
    		echo "签名不合法或者签名错误";die;
    	}

    	echo "sign签名成功";
    }

    //请求登录的接口换去token值
    public function login(Request $request)
    {
    	$params=$request->param();//获取接口请求的所有参数
    	//用户名
    	$username=$params['username'];

    	$password=$params['password'];

    	$user=Db::query('select * from user where username=? and password=?',[$username,md5($password)]);
    	
        // var_dump($user);

        if(!empty($username)){
        	$userId=$user[0]['id'];

        	//更新在本地生成token值
        	Db::query('update user set token=replace(uuid(),"-",""),expired_at=? where id=?',[time()+7200,$userId]);

        	$user=Db::query('select * from user where id=?',[$userId]);
        }

        $return=[
            'msg' =>'登录成功',
            'data'=>[
                'token'=>$user[0]['token']
            ]
        ];
        return json($return);die;
    }

    public function checkToken(Request $request)
    {
    	$params=$request->param();

    	if(!isset($params['token']) || empty($params['token'])){
    		echo 'token不存在或者为空';
    	}

    	$token=$params['token'];

    	$data=Db::query('select id,token,expired_at from user where token=?',[$token]);

    	if(empty($data)){  
    		echo "token值不合法";die;
    	}
        
        if($data[0]['expired_at']<time()){
        	echo "token过期";die;
        }
        
    	echo "token验证成功";
    }

    public function testApi()
    {
        $curl=curl_init();//初始化curl会话
        // echo $curl;die;
        // curl_setopt($curl,CURLOPT_URL,'http://www.baidu.com');
        curl_setopt($curl,CURLOPT_URL,'http://www.home.com/api/api/login');
        // 设置post请求数据
        curl_setopt($curl,CURLOPT_POST,true);

        // 传递post请求的数据
        $params=[
            'username'=>'admin',
            'password'=>'123'
        ];
        curl_setopt($curl,CURLOPT_POSTFIELDS,$params);
        
        $output=curl_exec($curl);
        var_dump($output);die;
        
        $out=file_get_contents('http://www.home.com/index.php');
        echo $out; 
    }

    public function testPost()
    {
        $post=$_POST;
        return json($post);
    }

    //获取学生列表的接口
    public function getStudentsList(Request $request)
    {
        $return=[
            'code'=>200,
            'msg' =>'成功'
        ];
        //验证token值
        $params=$request->param();//接受所有的参数
 
        if(!isset($params['token']) || empty($params['token'])){
            $return=[
                'code'=>500,
                'msg' =>'token不存在或者为空'
            ];
            return json($return);
        }

        $token=$params['token'];

        $data=Db::query('select id,token,expired_at from user where token=?',[$token]);

        if(empty($data)){
            $return=[
                'code'=>500,
                'msg' =>'token值不合法'
            ];
            return json($return);
        }

        if($data[0]['expired_at']<time()){
            $return =[
                'code'=>501,
                'msg' =>'token值已过期'   
            ];
            return json($return);
        }

        //走通token值获取学生列表并返回
        
        $students=Db::query('select * from students');

        $return['data']=$students;

        return json($return);
    }
}
?>