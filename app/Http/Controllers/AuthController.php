<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->input('email'))->first();

            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return redirect(route('login'))->withErrors([
                    'login' => 'Email or password is incorrect!'
                ])->withInput();
            }

            Auth::login($user);

            return redirect('/dashboard');
        }

        return view('auth/login');
    }

    public function register(Request $request)
    {
        //TODO
        if ($request->isMethod('post')) {
            //validate request
            //create user
            //login user or send activate email
            //redirected to dashboard/login
            $this->validate($request, [
                'full name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'retype password' => 'required'
            ]);

            $name = $request['full name'];
            $email = $request['email'];
            $password = $request['password'];
            $retype_password = $request['retype password'];
                
            if($password === $retype_password)
            {
                $user = User::create([
                    'full name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password)
                ]);
            }
            else 
            {
                return redirect(route('register'))->withErrors([
                    'register' => 'Password and Retype password does not match!'
                ])->withInput();
            }               
            
            Auth::login($user);

            return redirect('/dashboard');
        }

        //return view register
        return view('auth/register');
    }
}