<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;
<<<<<<< Updated upstream
use Illuminate\Support\Facades\Session;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
=======
use App\Http\Requests\RegisterRequest;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input)
    {
        // RegisterRequest を使ってバリデーションだけ適用
        $request = new RegisterRequest();
        $request->merge($input);

        // バリデーション実行（失敗すれば例外が自動的に投げられる）
        $validated = app()->make(RegisterRequest::class);
        $validated->merge($input);
// バリデーション実行（失敗すれば例外が自動的に投げられる）
        $validated = app()->make(RegisterRequest::class);
        $validated->merge($input);
        app()->validator->validate($validated->all(), $validated->rules(), $validated->messages());

        // 登録処理
        $user = User::create([
>>>>>>> Stashed changes
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }

}
