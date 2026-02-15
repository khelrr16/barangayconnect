<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>

<body>
    <div class="container">

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <h2>Sign in</h2>
            
            <input type="email" 
                name="email" 
                placeholder="E-mail" 
                value="{{ old('email') }}" 
                required />

            <input type="password" 
                name="password" 
                placeholder="Password" 
                required />
            <button type="submit" class="btn btn-success">Login</button>

            @if($errors->login->any())
            <div class="note">
                @if(session('retryAfter'))
                    <div id="lockoutAlert">
                        Too many attempts. Please wait 
                        <span id="countdown">{{ session('retryAfter') }}</span> seconds.
                    </div>
                @else
                    @foreach ($errors->login->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                @endif
            </div>
            @endif
        </form>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</html>