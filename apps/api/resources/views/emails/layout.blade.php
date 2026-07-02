<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px; }
        .header { font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #1e293b; }
        .button { display: inline-block; padding: 10px 20px; background-color: #2563eb; color: #fff; text-decoration: none; border-radius: 5px; }
        .footer { margin-top: 30px; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">BMS Enterprise</div>
        @yield('content')
        <div class="footer">
            &copy; {{ date('Y') }} BMS Enterprise. All rights reserved.
        </div>
    </div>
</body>
</html>
