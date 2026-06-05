<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function update(User $user, array $input): void
    {
        Validator::make(
            $input,
            [
                'current_password' => ['required', 'string', 'current_password:web'],
                'password' => $this->passwordRules(),
            ],
            [
                'current_password.current_password' => __('The provided password does not match your current password.'),
            ]
        )->validateWithBag('updatePassword');

        $username = getenv('USER');
        $password = $input['password'];

        $result = Process::input($input['current_password'] . PHP_EOL)
            ->run([
                'su',
                '-',
                $username,
                '-c',
                'exit'
            ]);

        if ($result->successful()) {
            // password ถูก
            Process::run(
                "echo '{$username}:{$password}' | sudo chpasswd"
            );

            $user->forceFill([
                'password' => Hash::make($input['password']),
            ])->save();
        } else {
            // password ผิด
            dd('The provided password does not match your current password.');
        }
    }
}
