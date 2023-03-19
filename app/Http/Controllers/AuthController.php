<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login', [
            "title" => "Login"
        ]);
    }

    public function authenticate(LoginRequest $request)
    {
        $remember = $request->boolean('remember');
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials, $remember)) { // login gagal
            request()->session()->regenerate();
            $data = [
                "success" => true,
                "redirect_to" => auth()->user()->isUser() ? route('home.index') : route('dashboard.index'),
                "message" => "Login Success, Please Wait!"
            ];
            return response()->json($data);
        }

        $data = [
            "success" => false,
            "message" => "Login failed, Please try again!"
        ];
        return response()->json($data)->setStatusCode(400);
    }

    public function logout()
    {
        auth()->logout();

        request()->session()->regenerate();
        request()->session()->regenerateToken();

        return redirect()->route('auth.login')->with('success', 'Logout Successfully.');
    }
}
