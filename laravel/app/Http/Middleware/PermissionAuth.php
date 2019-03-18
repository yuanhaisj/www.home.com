<?php

namespace App\Http\Middleware;

use Closure;
use App\Tools\ToolsAdmin;

class PermissionAuth
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
        $session = $request->session();
        //获取当前用户的权限节点的url地址
        $urls=ToolsAdmin::getUrlsByUserId($session->get('user.user_id'));

        // dd(\Route::currentRouteName());//获取地址名称
        //当前路由名字
        $route = \Route::currentRouteName();

        //当前用户不是超管并且没有访问当前路由的权限
        if($session->get('user.is_super') !=2 && in_array($route,$urls)){
            return redirect('/403')->send();
        }
        
        return $next($request);
    }
}
