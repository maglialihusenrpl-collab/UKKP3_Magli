
{{-- Form autentikasi pengguna; kesalahan validasi ditampilkan di atas form. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Marketplace</title>

    <style>
        body {
            font-family: sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .card {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            width: 100%;
            max-width: 360px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
            box-sizing: border-box;
        }

        input[type=email],
        input[type=password] {
            width: 100%;
            padding: .6rem;
            margin: .3rem 0 1rem;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: .7rem;
            background: #2563eb;
            color: #fff;
            border: 0;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .error {
            color: #dc2626;
            font-size: .875rem;
            margin-bottom: 1rem;
        }

        .remember {
            margin-bottom: 1rem;
        }

        .register-link {
            display: block;
            margin-top: 1rem;
            color: #1d4ed8;
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Login</h2>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="username"
                required
                autofocus
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <div class="remember">
                <label>
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    Ingat saya
                </label>
            </div>

            <button type="submit">Masuk</button>
        </form>

        <a class="register-link" href="{{ route('register') }}">Belum punya akun? Daftar sebagai customer</a>
    </div>
</body>
</html>
