<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center">
    <div class="w-full max-w-md rounded-xl bg-white p-8 shadow">
        <h1 class="text-2xl font-semibold mb-6">Login POS Grosir</h1>

        @if ($errors->any())
            <div class="mb-4 rounded bg-red-100 p-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border px-3 py-2"
                    required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Password</label>
                <input type="password" name="password" class="w-full rounded border px-3 py-2" required>
            </div>
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" value="1">
                    <span>Ingat saya</span>
                </label>
            </div>
            <button type="submit" class="w-full rounded bg-blue-600 px-4 py-2 font-medium text-white">Masuk</button>
        </form>
    </div>
</body>

</html>
