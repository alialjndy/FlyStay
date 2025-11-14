<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملخص وتقييمات الفنادق</title>
    {{-- استخدام خطوط عصرية --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;800&display=swap" rel="stylesheet">
    {{-- أيقونات Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --main-color: #3498db;
            /* أزرق نقي وجذاب */
            --accent-color: #2ecc71;
            /* أخضر للنجاح/إيجابية */
            --bg-light: #f4f6f9;
            --text-dark: #34495e;
            --font-primary: 'Tajawal', sans-serif;
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
            padding: 40px 20px;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ----- القسم الرئيسي والملخص ----- */
        .summary-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .summary-header h1 {
            color: var(--main-color);
            font-weight: 800;
            margin-bottom: 5px;
        }

        /* ----- شبكة التقييمات (CSS Grid) ----- */
        .ratings-grid {
            display: grid;
            /* تصميم متجاوب: 3 أعمدة على الشاشات الكبيرة، 2 على المتوسطة، 1 على الصغيرة */
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        /* ----- بطاقة التقييم الفردية (Rating Card) ----- */
        .rating-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .rating-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .rating-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .rating-user-info {
            font-size: 1.1em;
            font-weight: 700;
            color: var(--text-dark);
        }

        .rating-stars-display {
            color: var(--rating-color, #ffc107);
            font-size: 1.3em;
        }

        .rating-description {
            color: #555;
            margin-top: 10px;
            padding: 15px;
            background: #fdfdff;
            border-left: 4px solid var(--accent-color);
            border-radius: 4px;
            font-size: 0.95em;
            min-height: 80px;
            /* لتوحيد مظهر البطاقات */
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .meta-data {
            font-size: 0.8em;
            color: #999;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .meta-data span {
            margin-left: 15px;
        }

        .no-ratings {
            text-align: center;
            color: #e74c3c;
            padding: 50px;
            border: 2px dashed #e74c3c;
            border-radius: 10px;
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <div class="main-container">
        <div class="summary-header">
            <h1>📊 ملخص تقييمات العملاء</h1>
            <p>استعراض تفاعلي لجميع التقييمات والآراء الواردة من مستخدمينا.</p>
        </div>

        @if($ratings->isEmpty())
            <div class="no-ratings">
                <i class="fas fa-exclamation-triangle fa-2x"></i>
                <h2>لا توجد تقييمات حالياً!</h2>
                <p>كن أول من يقيّم هذه الخدمة/الفندق.</p>
            </div>
        @else
            <div class="ratings-grid">
                {{-- الحلقة التكرارية لعرض كل تقييم --}}
                @foreach($ratings as $rating)
                    <div class="rating-card">
                        <div>
                            <div class="rating-header">
                                <span class="rating-user-info">
                                    <i class="fas fa-user-circle"></i>
                                    المستخدم: #{{ $rating->user_id }}
                                </span>
                                <span class="rating-stars-display" title="{{ $rating->rating }} نجوم">
                                    {{ str_repeat('★', $rating->rating) }}{{ str_repeat('☆', 5 - $rating->rating) }}
                                </span>
                            </div>

                            <div class="rating-description">
                                <i class="fas fa-comment-dots"></i>
                                {{-- العرض الآمن للوصف --}}
                                {{ $rating->description }}
                            </div>
                        </div>

                        <div class="meta-data">
                            <span><i class="fas fa-hotel"></i> الفندق: #{{ $rating->hotel_id }}</span>
                            <span><i class="fas fa-hashtag"></i> ID: {{ $rating->id }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>

</html>