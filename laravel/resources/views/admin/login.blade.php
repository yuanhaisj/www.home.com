<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/images/favicon.png" type="image/png">

    <title>后台管理系统</title>

    <link href="/css/style.default.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript" src="/js/app.js"></script>
</head>

<body class="signin">
<section>
  
    <div class="signinpanel">
        
        <div class="row">
            
            <div class="col-md-7">
                
                <div class="signin-info">
                    <div class="logopanel">
                        <h1><span>[</span> 后台管理系统 <span>]</span></h1>
                    </div><!-- logopanel -->
                
                    <div class="mb20"></div>
                
                    <p>
                        <img src="/images/admin-icon.png" width="300">
                    </p>
                </div><!-- signin0-info -->
            
            </div><!-- col-sm-7 -->
            
            <div class="col-md-5" id="login">
                
                <form method="post" action="index.html" onsubmit="return false">
                    <h4 class="nomargin">登录</h4>
                    <p class="mt5 mb20"></p>
                
                {{csrf_field()}}

                    <input type="text" class="form-control uname" placeholder="用户名" name="username" />
                    <input type="password" class="form-control pword" placeholder="密码" name="password" />
                    <a href=""><small>忘记密码？</small></a>

                    <div v-if="error_show" style="color:red;">{error_msg}</div>
                    <button class="btn btn-success btn-block" v-on:click="login">立即登录</button>
                    
                </form>
            </div><!-- col-sm-5 -->
            
        </div><!-- row -->
        
        <div class="signup-footer">
            <div class="pull-left">
                &copy; 2014. All Rights Reserved. Bracket Bootstrap 3 Admin Template
            </div>
        </div>
        
    </div><!-- signin -->
  
</section>

<script src="/js/vue.js"></script>
<script>

  var login=new Vue({
      el:"#login",
      delimiters:['{','}'],
      data:{error_show:false,error_msg:''},
      methods:{
        login:function(){
            var username=$("input[name=username]").val();
            var password=$("input[name=password]").val();
            var token   =$("input[name=_token]").val();
            var that=this;
            // alert(username);
            if(username== '' || password==''){
                that.error_show=true;
                that.error_msg ="用户名或密码不能为空";
                return false;
            }

            $.ajax({
                url:"/admin/doLogin",
                type:"post",
                dataType:"json",
                data:{
                    username:username,password:password,_token:token
                },
                
                success:function(res){
                    if(res.code==2000){
                        that.error_show=false;
                        window.location.href="/admin/home";
                    }else{
                        that.error_show=true;
                        that.error_msg=res.msg;
                        return false;
                    }
                },
                error:function(res){

                }
            })
        }
      }
  });

</script>

</body>
</html>
