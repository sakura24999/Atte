<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Http\Requests\LoginRequest;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Mailer\Event\FailedMessageEvent;
use Illuminate\Validation\ValidationException;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        config(['fortify.home' => '/login']);

        Fortify::authenticateUsing(function (Request $request) {
            Log::info('========== LOGIN ATTEMPT START ==========');

            try {
                Log::info('Request data:', [
                    'all_data' => $request->all(),
                    'email' => $request->email,
                    'has_password' => $request->has('password')
                ]);

                $loginRequest = new LoginRequest();

                Log::info('LoginRequest created successfully');

                try {
                    Log::info('Validation setting:', [
                        'rules' => $loginRequest->rules(),
                        'message' => $loginRequest->messages(),
                        'attributes' => $loginRequest->attributes()
                    ]);


                    $loginRequest->validateResolved();

                    Log::info('Validation PASSED');
                } catch (ValidationException $e) {
                    Log::error('Validation FAILED', [
                        'errors' => $e->errors(),
                        'validator_messages' => $e->validator->getMessageBag()->toArray()
                    ]);
                    throw $e;
                }

                $user = User::where('email', $request->email)->first();
                Log::info('User lookup result:', ['found' => !is_null($user)]);

                if ($user && Hash::check($request->password, $user->password)) {
                    Log::info('Authentication successful for user:', [
                        'user_id' => $user->id
                    ]);
                    return $user;
                }
                Log::info('Authentication failed - invalid credentials');

                return null;
            } catch (ValidationException $e) {
                Log::error('Final validation error:', [
                    'errors' => $e->errors(),
                ]);

                return null;
            } catch (Exception $e) {
                Log::error('Unexpected error', [
                    'message' => $e->getMessage(),
                    'class' => get_class($e)
                ]);
                return null;
            } finally {
                Log::info('========== LOGIN ATTEMPT END ==========');
            }
        });
    }
}
