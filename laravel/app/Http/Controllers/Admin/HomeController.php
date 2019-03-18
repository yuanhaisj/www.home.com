<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //后台首页
    public function home()
    {
    	// echo \Route::has('admin.home')?route('admin.home'):"没有";
    	// dd(\App\Model\Permissions::getMeuns());
    	return view('admin.home');
    }
}
