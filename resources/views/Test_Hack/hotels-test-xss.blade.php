<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة الفنادق | فندقك</title>

    {{-- خطوط وأيقونات --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- مكتبات الحركة --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --bg-body: #f8f9fa;
            --bg-card: #ffffff;
            --text-primary: #222;
            --text-secondary: #555;
            --brand-primary: #007bff;
            --brand-secondary: #28a745;
            --rating-color: #ffc107;
            --border-color: rgba(0, 0, 0, 0.1);
            --shadow-card: 0 4px 15px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        [data-theme="dark"] {
            --bg-body: #121212;
            --bg-card: #1e1e1e;
            --text-primary: #f0f0f0;
            --text-secondary: #bbb;
            --brand-primary: #bb86fc;
            --brand-secondary: #03dac6;
            --border-color: rgba(255, 255, 255, 0.1);
            --shadow-card: 0 4px 15px rgba(0, 0, 0, 0.4);
            --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.6);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg-body);
            color: var(--text-secondary);
            margin: 0;
            padding: 0;
            transition: background-color 0.4s, color 0.4s;
        }

        a {
            text-decoration: none;
            color: var(--brand-primary);
        }

        /* ============ HERO SECTION ============ */
        .hero-header {
            position: relative;
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 110%;
            background-image: url('https://images.unsplash.com/photo-1501117716987-c8e1ecb2103c?auto=format&fit=crop&w=1650&q=80');
            background-size: cover;
            background-position: center;
            filter: brightness(0.5);
            transform: scale(1.1);
            animation: zoomHero 15s ease-in-out infinite alternate;
        }

        @keyframes zoomHero {
            from {
                transform: scale(1.05);
            }

            to {
                transform: scale(1.15);
            }
        }

        .hero-overlay-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.3));
            z-index: 1;
        }

        .page-header {
            z-index: 2;
            text-align: center;
            color: white;
            position: relative;
        }

        .logo {
            font-size: 3em;
            font-weight: 900;
            color: var(--brand-primary);
            text-shadow: 1px 1px 8px rgba(0, 0, 0, 0.4);
        }

        .hero-title {
            font-size: 2.8em;
            font-weight: 900;
            margin: 15px 0;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.7);
        }

        .hero-subtitle {
            font-size: 1.3em;
            opacity: 0.9;
            margin-bottom: 25px;
        }

        .hero-btn {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            color: white;
            padding: 14px 35px;
            border-radius: 50px;
            font-weight: 700;
            transition: transform 0.3s ease, opacity 0.3s;
        }

        .hero-btn:hover {
            transform: scale(1.07);
            opacity: 0.9;
        }

        /* ============ MAIN CONTENT ============ */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .hotels-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .hotel-card {
            background: var(--bg-card);
            border-radius: 16px;
            box-shadow: var(--shadow-card);
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
        }

        .hotel-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        .card-image-container {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .hotel-card:hover .card-image {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.5em;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .card-rating {
            color: var(--rating-color);
            font-size: 1.2em;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card-description {
            color: var(--text-secondary);
            font-size: 0.95em;
            line-height: 1.6;
            min-height: 60px;
            margin-bottom: 15px;
        }

        .card-link {
            display: inline-flex;
            align-items: center;
            color: var(--brand-primary);
            font-weight: bold;
        }

        .card-link i {
            margin-right: 5px;
            transition: margin-right 0.3s;
        }

        .card-link:hover i {
            margin-right: 0;
            margin-left: 5px;
        }

        /* ============ TOGGLE BUTTON ============ */
        #theme-toggle {
            position: fixed;
            top: 25px;
            left: 25px;
            z-index: 1000;
            background-color: var(--bg-card);
            color: var(--brand-primary);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.4em;
            cursor: pointer;
            box-shadow: var(--shadow-card);
            transition: all 0.3s;
        }

        #theme-toggle:hover {
            transform: scale(1.1);
            color: var(--text-primary);
        }

        /* ============ FOOTER ============ */
        footer {
            background: var(--bg-card);
            color: var(--text-secondary);
            text-align: center;
            padding: 40px 20px;
            border-top: 1px solid var(--border-color);
            margin-top: 60px;
        }

        footer a {
            color: var(--brand-primary);
            margin: 0 10px;
            font-size: 1.3em;
        }

        footer a:hover {
            color: var(--brand-secondary);
        }

        /* ---------------------------------------------------- */
        /* 🛑 أنماط الترقيم المُحسّنة (إخفاء الأسهم وتنسيق الأرقام) 🛑 */
        /* ---------------------------------------------------- */

        /* 1. إخفاء أزرار الأسهم (السابق والتالي) */
        .pagination-area .page-item:first-child,
        .pagination-area .page-item:last-child {
            display: none !important;
        }

        /* 2. إخفاء النقاط الثلاث (الفاصل Ellipsis) */
        /* يستهدف النقاط التي تكون معطلة (disabled) */
        .pagination-area .page-item.disabled span {
            display: none !important;
        }

        /* 3. تنسيق حاوية الأرقام لتوسيطها بشكل جيد وإزالة النص المصاحب */
        .pagination-area nav>div:first-child {
            display: none;
            /* إخفاء نص "Showing X to Y of Z results" */
        }

        .pagination-area nav {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 0;
            /* مسافة أعلى وأسفل الترقيم */
        }

        /* 4. تنسيق أزرار الأرقام (دوائر متجاورة) */
        .pagination-area .page-item {
            display: inline-block;
            padding: 0;
            /* إزالة أي padding غير مرغوب فيه */
        }

        /* 5. تنسيق رابط/زر الرقم نفسه */
        .pagination-area .page-item:not(.disabled) .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            font-weight: 700;
            font-size: 1em;
            color: var(--text-primary);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            /* دوائر */
            background-color: var(--bg-card);
            margin: 0 4px;
            /* مسافة بسيطة بين الأرقام */
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        /* 6. تنسيق الزر النشط حالياً */
        .pagination-area .page-item.active .page-link {
            background-color: var(--brand-primary) !important;
            color: white !important;
            border-color: var(--brand-primary) !important;
            transform: scale(1.1);
        }

        /* 7. تأثير التمرير (Hover) */
        .pagination-area .page-item .page-link:not(.active):hover {
            background-color: var(--brand-primary);
            color: white;
            border-color: var(--brand-primary);
            transform: scale(1.05);
        }
    </style>
</head>

<body data-theme="light">

    <button id="theme-toggle" title="تبديل الوضع الليلي">
        <i class="fas fa-moon"></i>
    </button>

    <header class="hero-header">
        <div class="hero-bg-image"></div>
        <div class="hero-overlay-gradient"></div>
        <div class="page-header" data-aos="fade-down">
            <div class="logo animate__animated animate__fadeInDown">
                <i class="fas fa-bed"></i> فندق<span style="color: var(--brand-secondary);">ك</span>
            </div>
            <h1 class="hero-title">استكشف أفضل وجهات الإقامة</h1>
            <p class="hero-subtitle">وجهتك القادمة بانتظارك، اختر الفندق المثالي اليوم.</p>
            <a href="#hotels-list" class="hero-btn">ابدأ البحث الآن</a>
        </div>
    </header>

    <main class="main-container" id="hotels-list">
        <p style="text-align:center; margin-bottom:30px;">
            عرض {{ $hotels->total() }} فندق متوفر
        </p>

        @if($hotels->isEmpty())
            <p class="text-center text-danger">لا توجد فنادق متاحة حالياً.</p>
        @else
            <div class="hotels-grid">
                @foreach($hotels as $hotel)
                    <div class="hotel-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="card-image-container">
                            <img src="https://picsum.photos/400/300?random={{ $hotel->id }}" alt="{{ $hotel->name }}"
                                class="card-image">
                        </div>
                        <div class="card-body">
                            <h2 class="card-title">{{ $hotel->name }}</h2>
                            <div class="card-rating">
                                {{ str_repeat('★', $hotel->rating) }}{{ str_repeat('☆', 5 - $hotel->rating) }}
                                ({{ $hotel->rating }}/5)
                            </div>
                            <p class="card-description">{{ $hotel->description }}</p>
                            <a href="{{ route('hotels.show', $hotel->id) }}" class="card-link">
                                عرض التفاصيل <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-area" style="text-align:center; margin-top:40px;">
                {{ $hotels->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </main>

    <footer>
        <p>© {{ date('Y') }} فندقك - جميع الحقوق محفوظة.</p>
        <div>
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </footer>

    {{-- مكتبات JavaScript --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // AOS Animation
            AOS.init({ once: true, duration: 800 });

            // Theme Toggle
            const btn = document.getElementById('theme-toggle');
            const saved = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', saved);
            btn.innerHTML = saved === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';

            btn.addEventListener('click', () => {
                const newTheme = document.body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                document.body.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                btn.innerHTML = newTheme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            });
        });
    </script>
</body>

</html>