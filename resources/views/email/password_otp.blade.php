<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial; background:#f5f5f5; padding:20px; }
        .box { background:#fff; padding:25px; border-radius:8px; }
        .otp { font-size:32px; letter-spacing:6px; font-weight:bold; color:#6b1a1a; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Password Reset OTP</h2>
        <p>Use the OTP below to reset your password:</p>
        <div class="otp">{{ $otp }}</div>
        <p>This OTP expires in <strong>5 minutes</strong>.</p>
    </div>
</body>
</html>
