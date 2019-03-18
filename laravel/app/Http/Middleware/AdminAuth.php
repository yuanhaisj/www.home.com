<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Model\Permissions;
use App\Tools\ToolsAdmin;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $params=$request->all();
        // dd($params);
        //判断用户是否登陆
        $session=$request->session();
  
        if(!$session->has('user')){//如果用户未登录。调到登陆页面
            return redirect('/admin/login')->send();
        }

        // $urls=ToolsAdmin::getUrlsByUserId($session->get('user.user_id'));

        //完成视图的共享
        View::share('username',$session->get('user.username'));
        View::share('user_pic',$session->get('user.image_url'));

        //左侧菜单视图共享
        $user = $session->get('user');
        View::share('menus',Permissions::getMeuns($user));
        return $next($request);
    }
}
