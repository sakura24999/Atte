<?php

namespace App\Actions\Fortify;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Contracts\CreatesNewUsers;

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
        Log::debug('Creating new user', [
            'input' => $input,
            'mail_settings' => [
                'host' => config('mail.host'),
                'post' => config('mail.port'),
            ]
        ]);

        $validator = Validator::make(
            $input,
            (new RegisterRequest())->rules(),
            (new RegisterRequest())->messages()
        );

        $validated = $validator->validate();

        $name = str_replace('　', ' ', $input['name']);

        $name = preg_replace('/\s+/', ' ', $name);

        $name = trim($input['name']);
        $nameParts = explode(' ', $name);

        if (count($nameParts) >= 2) {
            $lastName = $nameParts[0];
            $firstName = $nameParts[1];
        } else {
            $lastName = $name;
            $firstName = '';
        }

        Log::info('Name parts:', [
            'original' => $validated['name'],
            'processed' => $name,
            'lastName' => $lastName,
            'firstName' => $firstName
        ]);

        return User::create([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
