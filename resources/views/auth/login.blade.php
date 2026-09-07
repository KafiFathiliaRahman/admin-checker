<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Admin Checker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: var(--canvas); }
        .login-card {
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--white);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .06);
        }
        .brand-mark { background: var(--yellow); }
    </style>
</head>
<body class="min-h-dvh flex items-center justify-center">
    <form method="POST" action="{{ route('login') }}" class="login-card w-full max-w-md p-10">
        @csrf
        <div class="flex flex-col items-center gap-3 mb-8">
            <span class="brand-mark text-white w-10 h-10 rounded-xl grid place-items-center font-black text-lg">AC</span>
            <h1 class="text-2xl font-bold" style="color:var(--yellow-dark)">Admin Checker</h1>
            <p class="text-sm" style="color:var(--muted)">Masuk untuk akses dashboard</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 text-sm" style="color:#b91c1c">{{ $errors->first('email') }}</div>
        @endif

        <div class="mb-5">
            <label class="block text-xs font-semibold mb-2" style="color:var(--muted)">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full h-11 px-3 rounded-lg border"
                style="border-color:var(--line); color:var(--ink); background:var(--yellow-soft)"
                placeholder="admin@admin-checker.test" />
        </div>

        <div class="mb-6">
            <label class="block text-xs font-semibold mb-2" style="color:var(--muted)">Password</label>
            <input type="password" name="password" required autocomplete="current-password"
                class="w-full h-11 px-3 rounded-lg border"
                style="border-color:var(--line); color:var(--ink); background:var(--yellow-soft)"
                placeholder="********" />
        </div>

        <div class="flex items-center justify-between mb-8 text-sm">
            <label class="flex items-center gap-2" style="color:var(--ink)">
                <input type="checkbox" name="remember" value="1"> Ingat saya
            </label>
        </div>

        <button type="submit" class="primary-button w-full" style="background:var(--yellow); color:var(--navy)">
            Masuk
        </button>
    </form>
</body>
</html>
