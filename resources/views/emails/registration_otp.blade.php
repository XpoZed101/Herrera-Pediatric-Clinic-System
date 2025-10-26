<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verification Code</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', Arial, sans-serif; color:#111; }
        .container { max-width: 600px; margin: 0 auto; padding: 24px; }
        .code { font-size: 24px; font-weight: 700; letter-spacing: 6px; background: #f3f4f6; display: inline-block; padding: 12px 16px; border-radius: 8px; }
        .muted { color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="margin:0 0 12px;">Verify your email</h2>
        <p>Use the verification code below to continue your registration:</p>
        <p class="code">{{ $code }}</p>
        <p class="muted">This code will expire in {{ $ttlMinutes }} minutes.</p>
        <p>If you didn't request this code, you can ignore this email.</p>
        <p style="margin-top:24px;" class="muted">Pediatric Clinic</p>
    </div>
</body>
</html>