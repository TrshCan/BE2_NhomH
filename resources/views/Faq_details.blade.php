<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $faq->title }}</title>
    <style>
        :root {
            --primary-color: #2e7d32;
            --background-color: #f5f5f5;
            --border-color: #e0e0e0;
            --text-color: #333333;
        }

        body {
            font-family: 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .faq-detail-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .faq-title {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .faq-content {
            font-size: 16px;
        }

        .faq-content ul {
            padding-left: 20px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .faq-detail-container {
                margin: 20px 10px;
                padding: 15px;
            }
            
            .faq-title {
                font-size: 20px;
            }
            
            .faq-content {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="faq-detail-container">
        <h1 class="faq-title">{{ $faq->title }}</h1>
        <div class="faq-content">
            {!! $faq->content !!}
        </div>
        <a href="/faq" class="back-link">Quay lại danh sách FAQ</a>
    </div>
</body>
</html>