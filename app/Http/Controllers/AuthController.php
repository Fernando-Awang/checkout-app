<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private function validation()
    {
        return Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
    public function login()
    {
        try {
            // validation
            $validation = $this->validation();
            if ($validation->fails()) {
                return back()->withInput()->with('error', $validation->errors()->first());
            }

            $cresidential = [
                'email' => request('email'),
                'password' => request('password'),
            ];

            $auth = Auth::attempt($cresidential);
            if (!$auth) {
                return back()->withInput()->with('error', 'account not found');
            }

            return redirect('/product')->with('success', 'login success');
        } catch (\Throwable $th) {
            return back()->withInput()->with('error', 'login error');
        }
    }
    public function logout()
    {
        try {
            Auth::logout();
            return redirect('/')->with('success', 'logout success');
        } catch (\Throwable $th) {
            return back()->withInput()->with('error', 'logout error');
        }
    }
}
