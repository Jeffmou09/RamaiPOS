<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Informasi Login Anda</title>
<style>
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    color: #333333;
}
.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #dddddd;
    border-radius: 5px;
}
.header {
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 1px solid #eeeeee;
    margin-bottom: 20px;
}
.credentials {
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.footer {
    font-size: 12px;
    text-align: center;
    color: #777777;
    margin-top: 30px;
}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Informasi Login Baru Anda</h2>
    </div>
    <p>Halo,</p>
    <p>Anda telah meminta informasi login untuk akun Anda. Kami telah membuat password baru untuk Anda:</p>
    <div class="credentials">
        <p><strong>Username:</strong> {{ $user->username }}</p>
        <p><strong>Password:</strong> {{ $newPassword }}</p>
    </div>
    <p>Silakan gunakan informasi di atas untuk login ke akun Anda. Kami sangat menyarankan Anda untuk segera mengganti password setelah berhasil login demi keamanan akun Anda.</p>
    <p>Jika Anda tidak meminta informasi ini, silakan segera hubungi administrator sistem.</p>
    <div class="footer">
        <p>Email ini dikirim secara otomatis, mohon untuk tidak membalas.</p>
        <p>© {{ date('Y') }} Toko Ramai</p>
    </div>
</div>
</body>
</html>