<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email Address</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>Please click the link below to verify your email address:</p>
    <p><a href="{{ $url }}">Verify Email Address</a></p>
    <p>If you did not create an account, no further action is required.</p>
</body>
</html>