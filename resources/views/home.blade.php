<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz | الرئيسية</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #14532d; 
            --primary-green: #16a34a;
            --bg-light: #f8fafc;
            --text-gray: #475569;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Cairo', sans-serif; }
        body { background: var(--bg-light); color: var(--primary-dark); }

        /* --- NAVBAR --- */
        nav {
            background: white;
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 80px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky; top: 0; z-index: 1000;
        }

        .logo { font-size: 22px; font-weight: 900; color: var(--primary-green); text-decoration: none; }
        
        .nav-auth { display: flex; gap: 15px; align-items: center; }
        .link-login { text-decoration: none; color: var(--primary-dark); font-weight: 700; font-size: 14px; }
        .btn-join { 
            background: var(--primary-dark); color: white; padding: 10px 25px; 
            border-radius: 8px; text-decoration: none; font-weight: 700; transition: 0.3s;
        }
        .btn-join:hover { background: var(--primary-green); }

        /* --- HERO SECTION --- */
        .hero {
            display: flex; justify-content: space-between; align-items: center;
            padding: 60px 80px; background: white; min-height: 80vh; gap: 40px;
        }

        .hero-text { max-width: 50%; }
        .hero-text h1 { font-size: 42px; line-height: 1.3; margin-bottom: 20px; color: var(--primary-dark); }
        .hero-text p { font-size: 18px; color: var(--text-gray); margin-bottom: 30px; }

        /* تنسيق الصورة الدائرية */
        .hero-image-container {
            position: relative;
            width: 450px;
            height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-image-container::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-green), var(--primary-dark));
            border-radius: 50%;
            opacity: 0.1;
            animation: pulse 4s infinite;
        }

        .hero-image-container img { 
            width: 400px; 
            height: 400px;
            object-fit: cover;
            border-radius: 50%; /* جعلها دائرية */
            border: 8px solid white;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            z-index: 2;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.1; }
            50% { transform: scale(1.05); opacity: 0.2; }
        }

        /* --- ICON BAR --- */
        .icon-bar {
            display: flex; justify-content: center; gap: 40px;
            padding: 30px; background: #fff; border-top: 1px solid #edf2f7;
        }
        .icon-item { color: #94a3b8; text-align: center; transition: 0.3s; cursor: pointer; }
        .icon-item:hover { color: var(--primary-green); }
        .icon-item i { font-size: 26px; display: block; margin-bottom: 5px; }
        .icon-item span { font-size: 11px; font-weight: 700; }

        /* --- SERVICES --- */
        .services { padding: 80px; text-align: center; }
        .services h2 { font-size: 30px; margin-bottom: 50px; color: var(--primary-dark); }

        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
        .service-card { 
            background: white; padding: 40px 30px; border-radius: 20px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: 0.3s;
            border-bottom: 4px solid transparent;
        }
        .service-card:hover { transform: translateY(-10px); border-color: var(--primary-green); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
        .service-card i { font-size: 35px; color: var(--primary-green); margin-bottom: 20px; }
        .service-card h3 { font-size: 20px; margin-bottom: 15px; }
        .service-card p { color: var(--text-gray); font-size: 15px; line-height: 1.6; }

        /* --- تنسيق قسم الأدوية المضاف --- */
        .medicines-section { padding: 60px 80px; text-align: center; }
        .medicines-section h2 { font-size: 30px; margin-bottom: 40px; color: var(--primary-dark); }
        .med-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.02); text-align: right; border-bottom: 4px solid #16a34a; transition: 0.3s; display: flex; flex-direction: column; }
        .med-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .med-img-wrapper { width: 100%; height: 180px; background: #f1f5f9; }
        .med-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        .med-body { padding: 20px; }
        .med-body h3 { font-size: 18px; margin-bottom: 8px; color: var(--primary-dark); }
        .med-body p { color: var(--text-gray); font-size: 14px; line-height: 1.5; }

        footer { background: var(--primary-dark); color: white; padding: 30px; text-align: center; font-size: 14px; }

        @media (max-width: 992px) {
            .hero { flex-direction: column; text-align: center; padding: 40px 20px; }
            .hero-text { max-width: 100%; margin-bottom: 40px; }
            .hero-image-container { width: 300px; height: 300px; }
            .hero-image-container img { width: 280px; height: 280px; }
            nav { padding: 15px 30px; }
            .services, .medicines-section { padding: 40px 20px; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="/" class="logo">🌿 AgroDz</a>
        <div class="nav-auth">
            <a href="{{ route('login') }}" class="link-login">تسجيل الدخول</a>
            <a href="{{ route('register') }}" class="btn-join">إنشاء حساب</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-text">
            <h1>منصة AgroDz الرقمية <br> لخدمة الفلاحة والبيطرة</h1>
            <p>نحن نوفر لك الحلول التقنية للربط بين الفلاحين والموزعين والأطباء البيطريين، لضمان تواصل فعال ورعاية صحية متكاملة لثرواتكم الحيوانية.</p>
            <a href="{{ route('register') }}" class="btn-join" style="padding: 15px 40px; font-size: 16px;">ابدأ تجربتك الآن</a>
        </div>
        <div class="hero-image-container">
            <img src="{{ asset('images/hero.png') }}" alt="AgroDz Illustration">
        </div>
    </section>

    <div class="icon-bar">
        <div class="icon-item"><i class="fas fa-home"></i><span>الرئيسية</span></div>
        <div class="icon-item"><i class="fas fa-file-medical"></i><span>طلبات الأدوية</span></div>
        <div class="icon-item"><i class="fas fa-user-md"></i><span>استشارات بيطرية</span></div>
        <div class="icon-item"><i class="fas fa-truck-loading"></i><span>شبكة التوزيع</span></div>
    </div>

    <section class="services" id="services">
        <h2>اكتشف خدماتنا</h2>
        <div class="cards-grid">
            <div class="service-card">
                <i class="fas fa-stethoscope"></i>
                <h3>استشارات بيطرية</h3>
                <p>تواصل مباشر مع نخبة من الأطباء البيطريين للحصول على تشخيص دقيق ورعاية فورية لقطيعك.</p>
            </div>
            <div class="service-card">
                <i class="fas fa-pills"></i>
                <h3>الأدوية واللقاحات</h3>
                <p>طلب الأدوية البيطرية مباشرة من الموزعين وتتبع حالة شحنتك والكميات المتاحة في السوق.</p>
            </div>
            <div class="service-card">
                <i class="fas fa-sync-alt"></i>
                <h3>ربط ذكي</h3>
                <p>تنسيق آلي بين الفلاح والموزع والبيطري لضمان سرعة الاستجابة وتوفير المستلزمات الضرورية.</p>
            </div>
        </div>
    </section>

    <section class="medicines-section">
        <h2>أمثلة على الأدوية البيطرية المتوفرة</h2>
        <div class="cards-grid">
            
            <div class="med-card">
                <div class="med-img-wrapper">
                    <img src="{{ asset('images/med1.png') }}" alt="Antibiotic">
                </div>
                <div class="med-body">
                    <h3>مضادات حيوية واسعة الطيف</h3>
                    <p>أدوية فعالة لعلاج الالتهابات التنفسية والمعوية الحادة والوقاية من العدوى البكتيرية في القطعان.</p>
                </div>
            </div>

            <div class="med-card">
                <div class="med-img-wrapper">
                    <img src="{{ asset('images/med2.png') }}" alt="Vitamins">
                </div>
                <div class="med-body">
                    <h3>فيتامينات ومكملات نمو</h3>
                    <p>مركبات وفيتامينات (مثل AD3E) مخصصة لرفع مناعة الماشية وتحسين معدلات الخصوبة والإنتاج.</p>
                </div>
            </div>

            <div class="med-card">
                <div class="med-img-wrapper">
                    <img src="{{ asset('images/med3.png') }}" alt="Anti-parasites">
                </div>
                <div class="med-body">
                    <h3>مضادات الطفيليات والجرب</h3>
                    <p>جرعات علاجية مركزة ومحاليل للقضاء الفوري على الطفيليات الداخلية والخارجية كالجرب والقراد.</p>
                </div>
            </div>

        </div>
    </section>

    <footer>
        <p>حقوق النشر © 2026 AgroDz | صنع باحترافية لدعم الفلاحة الوطنية</p>
    </footer>

</body>
</html>