<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // 登録処理
        // 例：ユーザー作成
        $data = $request->validated();

        // ユーザーモデルに合わせて調整
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return redirect()->intended('/');
    }

    public function login(LoginRequest $request)
    {
        // 登録処理
        // 例：ユーザー作成
        $data = $request->validated();

        // ユーザーモデルに合わせて調整
        User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return redirect()->intended('/');
    }
}
