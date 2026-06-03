<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>انتقال به درگاه سیزپی</title>
    <style>
        body {
            font-family: Tahoma, sans-serif;
            direction: rtl;
            text-align: center;
            padding: 2rem;
            color: #374151;
        }
    </style>
</head>
<body>
    <p>در حال اتصال به درگاه بانکی سیزپی. لطفاً صبر کنید...</p>

    <form name="frmPay" method="post" action="{{ $paymentUrl }}">
        <input type="hidden" name="MerchantID" value="{{ $merchantId }}">
        <input type="hidden" name="TerminalID" value="{{ $terminalId }}">
        <input type="hidden" name="Token" value="{{ $token }}">
        <input type="hidden" name="SignData" value="{{ $signData }}">
        <noscript>
            <p><button type="submit">پرداخت</button></p>
        </noscript>
    </form>

    <script>document.frmPay.submit();</script>
</body>
</html>
