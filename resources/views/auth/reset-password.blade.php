<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - System</title>
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { max-width: 420px; width: 100%; background: #fff; border-radius: 14px; box-shadow: 0 10px 40px rgba(0,0,0,.12); overflow: hidden; }
        .header { padding: 28px; color: #fff; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .body { padding: 28px; }
        .form-group { margin-bottom: 14px; }
        label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #333; }
        input { width: 100%; border: 2px solid #e0e0e0; border-radius: 8px; padding: 12px; }
        input:focus { outline: none; border-color: #667eea; }
        .btn { width: 100%; border: none; color: #fff; padding: 12px; border-radius: 8px; font-weight: 600; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .alert { padding: 10px 12px; border-radius: 8px; margin-bottom: 14px; font-size: 14px; }
        .alert-danger { background: #fee; color: #c33; border: 1px solid #fcc; }
        .alert-info { background: #e7f3ff; color: #0066cc; border: 1px solid #b8daff; }
        .links { margin-top: 14px; text-align: center; font-size: 14px; }
        .links a { color: #667eea; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2 class="text-lg font-bold">Reset Password</h2>
            <p class="text-sm opacity-90">Enter the code sent to your email.</p>
        </div>
        <div class="body">
            @if(session('status'))
                <div class="alert alert-info">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $email ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label for="code">6-digit Code</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" maxlength="6" required>
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>
                <button type="submit" class="btn">Reset Password</button>
            </form>

            <div class="links">
                <a href="{{ route('password.request') }}">Resend code</a>
            </div>
        </div>
    </div>
    @include('partials.loading-screen')
</body>
</html>
