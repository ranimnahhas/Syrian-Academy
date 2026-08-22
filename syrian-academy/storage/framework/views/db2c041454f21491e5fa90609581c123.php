<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .header {
            background: #4f46e5;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
            color: #1f2937;
        }
        .content p {
            line-height: 1.8;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .token-box {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            border: 2px solid #4f46e5;
        }
        .token-code {
            font-family: 'Courier New', monospace;
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
            letter-spacing: 2px;
            word-break: break-all;
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin: 10px 0;
        }
        .copy-btn {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .copy-btn:hover {
            background: #4338ca;
        }
        .instructions {
            background: #f0fdf4;
            border-right: 4px solid #22c55e;
            padding: 15px 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .instructions ol {
            margin: 10px 0;
            padding-right: 20px;
        }
        .instructions li {
            margin-bottom: 10px;
        }
        .button-container {
            text-align: center;
            margin: 20px 0;
        }
        .btn-primary {
            display: inline-block;
            background: #4f46e5;
            color: white !important;
            padding: 14px 40px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #4338ca;
        }
        .footer {
            background: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .footer a {
            color: #4f46e5;
            text-decoration: none;
        }
        .warning {
            background: #fef2f2;
            border-right: 4px solid #ef4444;
            padding: 15px 20px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 14px;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 إعادة تعيين كلمة المرور</h1>
        </div>
        <div class="content">
            <p>مرحباً <strong><?php echo e($user->name); ?></strong>،</p>
            <p>لقد تلقينا طلباً لإعادة تعيين كلمة المرور لحسابك. يرجى اتباع الخطوات التالية:</p>
            
            <div class="instructions">
                <ol>
                    <li>انسخ رمز التحقق أدناه</li>
                    <li>اذهب إلى التطبيق وأدخل رمز التحقق مع الإيميل وكلمة المرور الجديدة</li>
                </ol>
            </div>

            <div class="token-box">
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">🔑 رمز التحقق الخاص بك:</p>
                <div class="token-code" id="tokenCode"><?php echo e($token); ?></div>
                <button class="copy-btn" onclick="copyToken()">📋 نسخ الرمز</button>
            </div>

            <div class="warning">
                ⚠️ <strong>تنبيه:</strong> ينتهي صلاحية هذا الرمز خلال <strong>60 دقيقة</strong>.
            </div>

            <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">

            <div style="text-align: center;">
                <p style="color: #6b7280; font-size: 14px;">أو استخدم الرابط المباشر:</p>
                <a href="<?php echo e(url('/reset-password?token=' . $token . '&email=' . urlencode($email))); ?>" class="btn-primary">
                    اضغط هنا لإعادة التعيين
                </a>
            </div>

            <p style="color: #6b7280; font-size: 14px; margin-top: 30px; text-align: center;">
                إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذا البريد الإلكتروني.
            </p>
        </div>
        <div class="footer">
            <p>© <?php echo e(date('Y')); ?> تطبيقك. جميع الحقوق محفوظة.</p>
        </div>
    </div>

    <script>
        function copyToken() {
            const token = document.getElementById('tokenCode').innerText;
            navigator.clipboard.writeText(token).then(() => {
                const btn = document.querySelector('.copy-btn');
                btn.textContent = '✅ تم النسخ!';
                setTimeout(() => {
                    btn.textContent = '📋 نسخ الرمز';
                }, 3000);
            }).catch(() => {
                // Fallback for older browsers
                const range = document.createRange();
                range.selectNode(document.getElementById('tokenCode'));
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
                document.execCommand('copy');
                alert('تم نسخ الرمز!');
            });
        }
    </script>
</body>
</html><?php /**PATH C:\Users\Fayez\Documents\GitHub\Syrian-Academy\syrian-academy\resources\views/emails/reset-password.blade.php ENDPATH**/ ?>