<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email</title>
    <style>
        .email-container {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3490dc;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Verifikasi Email Anda</h2>
        <p>Halo {{ $notifiable->name }},</p>
        <p>Silakan klik tombol berikut untuk memverifikasi alamat email Anda:</p>
        
        <a href="{{ $verificationUrl }}" class="button">
            Verifikasi Email
        </a>
        
        <p>Jika tombol di atas tidak bekerja, salin dan tempel URL berikut di browser Anda:</p>
        <p><small>{{ $verificationUrl }}</small></p>
        
        <p>Jika Anda tidak melakukan registrasi, abaikan email ini.</p>
        
        <p>Terima kasih,<br>
        Tim SITA PKL</p>
    </div>
</body>
</html>