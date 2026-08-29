<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="{{ $themeColor }}">
    <title>آفلاین — {{ $appName }}</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Tahoma, Vazirmatn, sans-serif;
            background: #f7f4ea;
            color: #333;
            text-align: center;
            padding: 24px;
        }
        .card {
            max-width: 360px;
            background: #fff;
            border-radius: 16px;
            padding: 32px 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }
        h1 { font-size: 20px; margin: 0 0 12px; }
        p { font-size: 15px; line-height: 1.8; margin: 0 0 20px; color: #555; }
        a {
            display: inline-block;
            background: {{ $themeColor }};
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>اتصال اینترنت برقرار نیست</h1>
        <p>صفحهٔ درخواستی روی این دستگاه ذخیره نشده. بعد از وصل شدن اینترنت دوباره تلاش کنید.</p>
        <a href="{{ url('/') }}">بازگشت به صفحه اصلی</a>
    </div>
</body>
</html>
