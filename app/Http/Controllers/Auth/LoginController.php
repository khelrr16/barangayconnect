<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use AuthenticatesUsers, ThrottlesLogins;

    protected $maxAttempts = 5;
    protected $decayMinutes = 1;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'email';
    }

    protected function authenticated(Request $request, $user)
    {
        $user->update(['last_login_at' => now()]);

        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->hasRole('clerk')) {
            return redirect()->intended(route('clerk.dashboard'));
        } elseif ($user->hasRole('committee_head')) {
            return redirect()->intended(route('committee.dashboard'));
        } elseif ($user->hasRole('resident')) {
            return redirect()->intended(route('resident.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    protected function redirectTo()
    {
        return route('dashboard'); // Fallback
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = Str::lower(trim($validated['email']));
        $code = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($code),
                'created_at' => now(),
            ]
        );

        Mail::raw(
            "Your password reset code is: {$code}\n\nThis code will expire in 15 minutes.",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('RBI System Password Reset Code');
            }
        );

        return redirect()->route('password.reset', ['email' => $email])
            ->with('status', 'A reset code was sent to your email.');
    }

    public function showResetPasswordForm(Request $request)
    {
        return view('auth.reset-password', [
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $email = Str::lower(trim($validated['email']));

        $resetRow = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRow) {
            return back()->withErrors([
                'code' => 'No reset code found for this email.',
            ])->withInput();
        }

        $createdAt = Carbon::parse($resetRow->created_at);
        $isExpired = now()->diffInMinutes($createdAt) > 15;
        if ($isExpired) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return back()->withErrors([
                'code' => 'Reset code has expired. Request a new one.',
            ])->withInput();
        }

        if (!Hash::check($validated['code'], $resetRow->token)) {
            return back()->withErrors([
                'code' => 'Invalid reset code.',
            ])->withInput();
        }

        DB::table('users')
            ->where('email', $email)
            ->update([
                'password' => Hash::make($validated['password']),
                'updated_at' => now(),
            ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('login')->with('status', 'Password has been reset. You can now sign in.');
    }

    public function registerStore(Request $request)
    {

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('resident');

        return redirect()->route('login')->with('status', 'Account created successfully. You can now sign in.');
    }
}
