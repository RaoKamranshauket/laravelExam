<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    //
    public function index()
    {
        return view('welcome');
    }
    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        if ($user) {
            //password check
            if (!Hash::check($request->password, $user->password)) {
                dd($request->password);
        exit;
                Session::flash('error_message', 'Invalid credentials given!');
                return redirect()->route('index');
            }

            // user exists

            if (Auth::attempt($request->only('email', 'password'))) {
                Session::flash('success_message', 'You are logged in successfully!');

                if (Gate::allows('is_admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif (Gate::allows('is_teacher')) {
                    return redirect()->route('admin.exams.register_index');
                } else {
                    return redirect()->route('admin.reporting.index');
                }
            }
        }
        Session::flash('error_message', 'Invalid credentials given!');
        return redirect()->route('index');
    }
}
