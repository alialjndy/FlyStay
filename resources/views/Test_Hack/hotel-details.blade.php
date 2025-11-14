<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الفندق</title>

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <style>
        /* ---------------------------------------- */
        /* 1. Variables & Reset */
        /* ---------------------------------------- */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --bg-light: #f8f9fa;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --font-primary: 'Tajawal', sans-serif;
            --shadow-light: 0 4px 15px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 8px 30px rgba(0, 0, 0, 0.15);
            --rating-color: #f1c40f;
            --transition-speed: 0.3s;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-primary);
            background: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            /* ضمان أن المحتوى لا يختفي أسفل الشاشة */
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ---------------------------------------- */
        /* 2. Wrapper & Layout */
        /* ---------------------------------------- */
        .main-wrapper {
            max-width: 1400px;
            margin: 30px auto;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
        }

        /* ---------------------------------------- */
        /* 3. Hero Section (تم تعديل الارتفاع هنا) */
        /* ---------------------------------------- */
        .hero-section {
            position: relative;
            /* 🎨 تم التعديل: استخدام min-height بدلاً من height الثابتة */
            min-height: 400px;
            overflow: hidden;
            display: flex;
            /* للتوسيط الأفقي والعمودي للمحتوى */
            align-items: center;
            justify-content: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(44, 62, 80, 0.6), rgba(44, 62, 80, 0.8));
            z-index: 2;
        }

        .hero-image {
            position: absolute;
            /* وضع الصورة خلف المحتوى */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.05);
            transition: transform 1.2s ease;
        }

        .hero-content {
            position: relative;
            /* لضمان ظهور المحتوى فوق الصورة */
            z-index: 3;
            text-align: center;
            color: #fff;
            padding: 20px;
        }

        /* ... أنماط hero-content الأخرى ... */

        /* ---------------------------------------- */
        /* 4. Details Grid */
        /* ---------------------------------------- */
        .details-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            padding: 40px;
        }

        .main-content {
            border-right: 1px solid #eee;
            padding-right: 40px;
        }

        .sidebar {
            /* في تخطيط Grid، هذا يضمن أن البطاقة تبدأ بعد المحتوى الرئيسي */
            padding-left: 20px;
        }

        /* ---------------------------------------- */
        /* 2. تنسيق بطاقة التقييم (Rating Card) */
        /* ---------------------------------------- */
        .rating-card {
            /* الأبعاد والخلفية */
            background-color: #fff;
            border-radius: 15px;
            /* حواف دائرية */
            padding: 30px 20px;
            /* مسافة داخلية جيدة */
            box-shadow: var(--shadow-light);
            /* ظل خفيف */
            border: 1px solid #eaeaea;
            /* حدود رقيقة */
            text-align: center;
            /* توسيط جميع محتويات البطاقة */

            /* الحركة والانتقال */
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
        }

        .rating-card:hover {
            /* تأثير طفيف عند التمرير لجذب الانتباه */
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        /* 3. تنسيق عناصر البطاقة الداخلية */

        /* عنوان البطاقة */
        .rating-card h3 {
            font-size: 1.4em;
            color: var(--primary-color);
            margin-bottom: 15px;
            font-weight: 700;
        }

        /* قيمة التقييم الرقمية (4.0 / 5) */
        .rating-value {
            font-size: 3.5em;
            /* حجم كبير */
            font-weight: 800;
            /* خط سميك جداً */
            color: var(--primary-color);
            margin: 10px 0;
        }

        /* نجوم التقييم */
        .rating-stars {
            font-size: 1.8em;
            /* حجم أكبر للنجوم */
            color: var(--rating-color);
            /* لون ذهبي/أصفر */
            letter-spacing: 5px;
            /* مسافة بين النجوم */
            margin-bottom: 15px;
        }

        /* النص المصاحب للتقييم */
        .rating-text {
            font-size: 1em;
            color: var(--text-muted);
            margin-bottom: 25px;
            /* مسافة قبل الزر */
        }

        /* زر الحجز */
        .rating-card button {
            background-color: var(--accent-color);
            /* لون مميز للحجز */
            color: #fff;
            border: none;
            padding: 12px 30px;
            font-size: 1.1em;
            font-weight: 500;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            /* جعل الزر يملأ عرض البطاقة */
            transition: background-color var(--transition-speed), transform 0.2s;
        }

        .rating-card button:hover {
            background-color: #c0392b;
            /* ظل أغمق عند التمرير */
            transform: translateY(-2px);
        }

        /* ---------------------------------------- */
        /* 5. Swiper Gallery (تم تعديل الارتفاع هنا) */
        /* ---------------------------------------- */
        .swiper-container {
            width: 100%;
            /* 🎨 تم التعديل: استخدام padding-bottom بنسبة مئوية للحفاظ على نسبة العرض إلى الارتفاع */
            padding-bottom: 75%;
            /* نسبة 4:3 (400px ارتفاع من 800px عرض) */
            height: 0;
            /* تعيين الارتفاع إلى صفر لإتاحة استخدام padding-bottom */
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
        }

        .swiper-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            height: 100%;
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
            transition: transform var(--transition-speed);
        }

        /* ... أنماط Swiper الأخرى ... */

        /* ---------------------------------------- */
        /* 6. Responsive */
        /* ---------------------------------------- */
        @media(max-width:992px) {
            .details-grid {
                grid-template-columns: 1fr;
                padding: 20px;
            }

            .main-content {
                border-right: none;
                padding-right: 0;
            }

            .sidebar {
                padding-left: 0;
            }
        }

        @media(max-width:576px) {
            .hero-section {
                min-height: 300px;
                /* تقليل الارتفاع الأدنى للهاتف */
            }

            .hero-content h1 {
                font-size: 2em;
            }

            .hero-meta {
                font-size: 1em;
            }

            .swiper-container {
                padding-bottom: 100%;
                /* اجعل المعرض مربعًا في الجوال */
            }
        }

        /* ... أنماط not-found ... */
    </style>
</head>

<body>

    <div class="main-wrapper">

        <div class="hero-section" data-aos="fade-in">
            <img src="https://picsum.photos/1400/700?random=1" alt="اسم الفندق" class="hero-image">
            <div class="hero-content" data-aos="fade-up" data-aos-delay="300">
                <h1>فندق التجربة</h1>
                <div class="hero-meta" data-aos="fade-up" data-aos-delay="500"><i class="fas fa-map-marker-alt"></i> ID:
                    #1</div>
            </div>
        </div>

        <div class="details-grid">
            <div class="main-content">

                <div class="section-block" data-aos="fade-up">
                    <h2 class="section-title"><i class="fas fa-camera"></i> معرض الصور</h2>
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img src="https://picsum.photos/800/600?random=2"
                                    alt="صورة الفندق"></div>
                            <div class="swiper-slide"><img src="https://picsum.photos/800/600?random=3"
                                    alt="صورة الفندق"></div>
                            <div class="swiper-slide"><img src="https://picsum.photos/800/600?random=4"
                                    alt="صورة الفندق"></div>
                            <div class="swiper-slide"><img src="https://picsum.photos/800/600?random=5"
                                    alt="صورة الفندق"></div>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>

                <div class="section-block" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> عن الفندق</h2>
                    <div class="description-content">
                        هذا نص تجريبي لوصف الفندق. يمكنك تعديل المحتوى حسب الحاجة.
                    </div>
                </div>

                <div class="section-block" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="section-title"><i class="fas fa-clipboard-list"></i> معلومات إضافية</h2>
                    <ul style="list-style:none;padding:0;">
                        <li style="margin-bottom:10px; padding:5px; border-bottom:1px dashed #eee;">
                            <i class="fas fa-check-circle" style="color:var(--secondary-color); margin-left:8px;"></i>
                            تاريخ الإنشاء: غير متوفر
                        </li>
                        <li style="margin-bottom:10px; padding:5px; border-bottom:1px dashed #eee;">
                            <i class="fas fa-check-circle" style="color:var(--secondary-color); margin-left:8px;"></i>
                            حالة الإتاحة: متاح للحجز
                        </li>
                    </ul>
                </div>

            </div>

            <div class="sidebar">
                <div class="rating-card" data-aos="fade-left" data-aos-delay="300">
                    <h3>التقييم العام</h3>
                    <div class="rating-value">4.0 / 5</div>
                    <div class="rating-stars">★★★★☆</div>
                    <p class="rating-text">بناءً على تقييمات العملاء.</p>
                    <button>احجز الآن</button>
                </div>
            </div>
        </div>

    </div>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ once: true, duration: 1000 });

            // تهيئة Swiper
            new Swiper('.swiper-container', {
                loop: true,
                autoplay: { delay: 4000, disableOnInteraction: false },
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                speed: 800
            });
        });
    </script>

</body>

</html>