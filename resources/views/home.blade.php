<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz | منصة الفلاحة الذكية</title>
    
    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #1a2e1a; 
            --primary-green: #16a34a;
            --accent-light: #f5ebe0; 
            --text-gray: #4b5563;
            --icon-navy: #344767; /* لون الأيقونات من الصورة */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Cairo', sans-serif; }

        body { background: #fdfaf7; color: var(--primary-dark); overflow-x: hidden; }

        /* --- NAVBAR --- */
        nav {
            position: sticky; top: 0; backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.8);
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 60px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); z-index: 1000;
        }

        .logo { font-size: 24px; font-weight: 900; color: var(--primary-green); text-decoration: none; }

        /* --- HERO SECTION --- */
        .hero {
            display: flex; justify-content: space-between; align-items: center;
            padding: 80px 80px 120px 80px; background: var(--accent-light);
            border-radius: 0 0 100px 100px; position: relative; min-height: 85vh;
        }

        .hero::after {
            content: ""; position: absolute; width: 600px; height: 600px;
            background: #2d3a2d; border-radius: 50%; top: -100px; left: -200px; z-index: 1;
        }

        .hero-text { max-width: 50%; z-index: 10; }
        .hero-text h1 { font-size: 55px; line-height: 1.2; margin-bottom: 20px; color: var(--primary-dark); }
        .hero-text p { font-size: 20px; color: var(--text-gray); margin-bottom: 35px; }

        .hero-image { z-index: 10; position: relative; }
        .hero-image img { width: 550px; filter: drop-shadow(15px 15px 30px rgba(0,0,0,0.15)); animation: float 4s ease-in-out infinite; }

        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }

        /* --- BUTTONS --- */
        .btn { display: inline-block; padding: 15px 40px; border-radius: 12px; font-weight: 700; text-decoration: none; transition: 0.3s; cursor: pointer; }
        .btn-primary { background: var(--primary-dark); color: white; box-shadow: 0 10px 20px rgba(26, 46, 26, 0.2); }
        .btn-outline { background: transparent; border: 2px solid var(--primary-dark); color: var(--primary-dark); margin-right: 15px; }

        /* --- NEW ICON BAR (From Image) --- */
        .icon-bar {
            display: flex; justify-content: center; gap: 35px;
            padding: 40px 0 10px 0; font-size: 28px; color: var(--icon-navy);
        }
        .icon-bar i { cursor: pointer; transition: 0.3s; }
        .icon-bar i:hover { color: var(--primary-green); transform: scale(1.1); }

        /* --- SERVICES CARDS --- */
        .services { padding: 40px 60px 100px 60px; text-align: center; }
        .services h2 { font-size: 36px; margin-bottom: 50px; position: relative; display: inline-block; color: var(--primary-green); }

        .cards-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .card { background: white; padding: 40px; border-radius: 25px; border: 1px solid #eee; transition: 0.4s; }
        .card:hover { transform: translateY(-15px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); border-color: var(--primary-green); }
        .card i { font-size: 40px; color: var(--primary-green); margin-bottom: 20px; }
        .card h3 { margin-bottom: 15px; font-size: 22px; }

        /* --- FOOTER --- */
        footer { background: var(--primary-dark); color: white; padding: 40px; text-align: center; border-radius: 50px 50px 0 0; }

        @media (max-width: 992px) {
            .hero { flex-direction: column; text-align: center; padding: 60px 20px; }
            .hero-text { max-width: 100%; margin-bottom: 50px; }
            .hero-image img { width: 100%; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="/" class="logo">🌿 AgroDz</a>
        <div class="nav-links">
            <a href="{{ route('login') }}" style="text-decoration:none; color:var(--primary-dark); margin-left:20px; font-weight:700;">تسجيل الدخول</a>
            <a href="{{ route('register') }}" style="background: var(--primary-green); color: white; padding: 10px 20px; border-radius: 8px; text-decoration:none; font-weight:700;">ابدأ مجاناً</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-text">
            <h1>أفضل الحلول الفلاحية <br> تحت سقف واحد</h1>
            <p>منصة AgroDz هي شريكك الذكي للربط بين الفلاحين، الموزعين، والأطباء البيطريين لضمان إنتاج أفضل وإدارة أسهل.</p>
            <div class="hero-btns">
                <a href="{{ route('register') }}" class="btn btn-primary">أنشئ حسابك الآن</a>
                <a href="#services" class="btn btn-outline">اكتشف خدماتنا</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/hero.png') }}" alt="AgroDz Dashboard">
        </div>
    </section>

    <!-- شريط الأيقونات العلوي المطلوب -->
    <div class="icon-bar">
        <i class="fas fa-home"></i>
        <i class="fas fa-box"></i>
        <i class="fas fa-envelope"></i>
        <i class="fas fa-user"></i>
        <i class="fas fa-user-plus"></i>
    </div>

    <section class="services" id="services">
        <h2>خدماتنا المتكاملة</h2>
        <div class="cards-container">
            <div class="card">
                <i class="fas fa-shopping-basket"></i>
                <h3>سوق المنتجات</h3>
                <p>بيع المنتجات الفلاحية الحيوانية و النباتية بكل سهولة وأمان.</p>
            </div>
            <div class="card">
                <i class="fas fa-stethoscope"></i>
                <h3>استشارات بيطرية</h3>
                <p>استشارات طبية للحيوانات وتواصل مباشر مع المختصين.</p>
            </div>
            <div class="card">
                <i class="fas fa-truck"></i>
                <h3>شبكة التوزيع</h3>
                <p>تنظيم الطلبات مع الموزعين لضمان وصول سلعتك في الوقت المناسب.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>جميع الحقوق محفوظة © 2026 AgroDz</p>
    </footer>

</body>
</html>