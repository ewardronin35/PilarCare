<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function __construct()
    {
        \Log::info('TestController constructor called');
        $this->middleware('guest')->except('logout');
    }

    public function test()
    {
        return 'Middleware is working!';
    }
}
