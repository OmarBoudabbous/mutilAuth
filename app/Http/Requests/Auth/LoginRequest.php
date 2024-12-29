<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Step 1: Ensure the user is not rate-limited (too many failed login attempts)
        $this->ensureIsNotRateLimited();
    
        // Step 2: Find the user by either email or name
        $user = User::where('email', $this->login) // Check if login matches email
                    ->orWhere('name', $this->login) // Check if login matches username
                    ->first(); // Retrieve the first matching user
    
        // Step 3: Check if the user exists and the password is correct
        if (!$user || !Hash::check($this->password, $user->password)) {
            // If user is not found or password doesn't match, hit the rate limiter
            RateLimiter::hit($this->throttleKey()); // Increment failed attempts for this throttle key
    
            // Throw a validation exception with an error message
            throw ValidationException::withMessages([
                'login' => trans('auth.failed'), // Translate and display the login failed message
            ]);
        }
    
        // Step 4: Log the user in if credentials are correct
        Auth::login($user, $this->boolean('remember')); // Log in the user, optionally remember them
    
        // Step 5: Clear the rate limiter after successful login
        RateLimiter::clear($this->throttleKey()); // Reset the failed login attempts
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
