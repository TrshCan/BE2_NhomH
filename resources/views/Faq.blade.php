<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Câu hỏi thường gặp</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2e7d32;
            --hover-color: #1b5e20;
            --background-color: #f5f5f5;
            --border-color: #e0e0e0;
            --text-color: #333333;
            --secondary-text: #666666;
        }

        body {
            font-family: 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .faq-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .faq-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }

        .faq-header h1 {
            color: var(--primary-color);
            font-size: 32px;
            margin: 0;
        }

        .faq-item {
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-question {
            background-color: white;
            padding: 15px 20px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid var(--primary-color);
        }

        .faq-question:hover {
            background-color: rgba(46, 125, 50, 0.05);
        }

        .faq-question .subtle {
            background-color: var(--primary-color);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
            margin-right: 10px;
            display: inline-block;
        }

        .faq-question .title {
            flex-grow: 1;
        }

        .faq-question .icon {
            transform: rotate(0deg);
            transition: transform 0.3s ease;
        }

        .faq-question.active .icon {
            transform: rotate(180deg);
        }

        .faq-answer {
            background-color: white;
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .faq-answer.active {
            padding: 20px;
            max-height: 1000px;
            border-top: 1px solid var(--border-color);
        }

        .faq-answer p {
            margin: 0 0 15px;
        }

        .faq-answer ul {
            margin: 0;
            padding-left: 20px;
        }

        .search-box {
            width: 100%;
            padding: 12px 20px;
            margin: 0 0 30px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .search-box:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .category-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .category-btn {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 30px;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-btn:hover, .category-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .faq-container {
                margin: 20px 10px;
                padding: 15px;
            }
            
            .faq-header h1 {
                font-size: 24px;
            }
            
            .faq-question {
                padding: 12px 15px;
                font-size: 15px;
            }
            
            .category-filter {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="faq-container">
        <div class="faq-header">
            <h1>Câu hỏi thường gặp</h1>
        </div>
        
        <div class="category-filter">
            <button class="category-btn active">Tất cả</button>
            <button class="category-btn">Cảnh báo lừa đảo</button>
            <button class="category-btn">Trả hàng</button>
            <button class="category-btn">Hoàn tiền</button>
            <button class="category-btn">Lỗi</button>
            <button class="category-btn">Thành viên mới</button>
            <button class="category-btn">Hủy đơn</button>
        </div>
        <div class="faq-list">
            @foreach ($faqs as $faq)
                <div class="faq-item" data-id="{{ $faq->id }}">
                    <div class="faq-question">
                        <span class="subtle">{{ $faq->subtle }}</span>
                        <span class="title">{{ $faq->title }}</span>
                     
                    </div>
                    <div class="faq-answer">
                        <p>{!! $faq->content !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');
            const searchBox = document.querySelector('.search-box');
            const categoryButtons = document.querySelectorAll('.category-btn');

            // Normalize Vietnamese text for search (remove diacritics)
            function normalizeText(text) {
                return text
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .toLowerCase();
            }

            // Handle search
            if (searchBox) {
                searchBox.addEventListener('input', function() {
                    const searchTerm = normalizeText(this.value);

                    faqItems.forEach(item => {
                        const questionElement = item.querySelector('.title');
                        const answerElement = item.querySelector('.faq-answer');

                        const questionText = questionElement ? normalizeText(questionElement.textContent) : '';
                        const answerText = answerElement ? normalizeText(answerElement.innerText) : '';

                        if (searchTerm === '' || questionText.includes(searchTerm) || answerText.includes(searchTerm)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // Handle category filter
            categoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    const category = normalizeText(this.textContent);

                    if (category === normalizeText('Tất cả')) {
                        faqItems.forEach(item => item.style.display = 'block');
                        return;
                    }

                    faqItems.forEach(item => {
                        const itemCategory = item.querySelector('.subtle')?.textContent || '';
                        if (normalizeText(itemCategory).includes(category)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });

            // Handle FAQ item click to redirect
            faqItems.forEach(item => {
                item.addEventListener('click', function() {
                    const faqId = this.getAttribute('data-id');
                    if (faqId) {
                        window.location.href = `/faq/${faqId}`;
                    }
                });
            });
        });
    </script>
</body>
</html>