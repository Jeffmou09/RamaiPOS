<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a4f;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .email-container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            width: 400px;
            padding: 40px;
        }
        .email-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .submit-btn {
            background-color: #343a4f;
            border-color: #343a4f;
        }
        .submit-btn:hover {
            background-color: #292f40;
            border-color: #292f40;
        }
        .back-to-login {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2 class="email-title">Lupa Password</h2>
        <p class="text-center mb-4">Masukkan email yang terdaftar untuk menerima username dan password Anda.</p>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('send.credentials') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="d-grid gap-2 col-8 mx-auto">
                <button type="submit" class="btn btn-primary submit-btn">Kirim</button>
            </div>
        </form>
        <div class="back-to-login">
            <a href="{{ route('login') }}">Kembali ke halaman login</a>
        </div>
    </div>
</body>
</html>