<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Customer - Marketplace</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .card {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .1);
            box-sizing: border-box;
        }

        h1 { margin-top: 0; font-size: 24px; }
        label { display: block; margin: 14px 0 5px; font-size: 14px; font-weight: 600; }
        input {
            width: 100%;
            padding: .7rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            margin-top: 20px;
            padding: .75rem;
            background: #2563eb;
            color: #fff;
            border: 0;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background: #1d4ed8; }
        .error { color: #dc2626; font-size: 13px; margin-top: 5px; }
        .login-link { display: block; margin-top: 16px; color: #1d4ed8; text-align: center; text-decoration: none; }
    </style>
</head>
<body>
    <main class="card">
        <h1>Daftar Customer</h1>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <label for="name">Nama</label>
            <input id="name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name">
            @error('name') <div class="error">{{ $message }}</div> @enderror

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email">
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Password (minimal 8 karakter)</label>
            <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <label for="password_confirmation">Konfirmasi password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">

            <button type="submit">Buat akun customer</button>
        </form>

        <a class="login-link" href="{{ route('login') }}">Sudah punya akun? Masuk</a>
    </main>
</body>
</html>