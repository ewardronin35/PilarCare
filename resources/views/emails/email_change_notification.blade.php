<!DOCTYPE html>
<html>
<head>
    <title>Email Change Notification</title>
</head>
<body>
    <p>Hello {{ $user->first_name }},</p>
    <p>This is a notification that your email has been changed from {{ $user->getOriginal('email') }} to {{ $user->email }}.</p>
    <p>If you did not make this change, please contact support immediately.</p>
    <p>Thank you.</p>
</body>
</html>
