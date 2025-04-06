<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Page</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background-color: #343a4f;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.login-container {
    background-color: white;
    border-radius: 5px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    width: 400px;
    padding: 40px;
}
.login-title {
    text-align: center;
    margin-bottom: 30px;
}
.login-btn {
    background-color: #343a4f;
    border-color: #343a4f;
}
.login-btn:hover {
    background-color: #292f40;
    border-color: #292f40;
}
</style>
</head>
<body>
<div class="login-container">
    <h2 class="login-title">Login</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="btn btn-primary login-btn">Login</button>
        </div>
    </form>
</div>
</body>
</html>