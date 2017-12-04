<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminLoginController extends Controller
{
	public function __construct() {
		$this->middleware('guest:admin');
	}

    public function showLoginForm() {
    	return view('auth.admin-login');
    }

    public function login() {
    	$this->validate($request, [
    		'email'	=> 'required|email',
    		'password' => 'required|min:6',
    	]);

    	if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
    		return redirect()->intended(route('/admin'));
    	}

    	return redirect()->back()->withInput($request->only('email', 'remember'));
    }

}