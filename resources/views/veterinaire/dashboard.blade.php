<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - لوحة البيطري</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        :root {
            --primary-dark: #14532d;
            --accent-green: #16a34a;
            --warning-red: #dc2626;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --text-main: #1e293b;
            --shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        body {
            background: var(--bg-light);
            color: var(--text-main);
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-dark), #064e3b);
            position: fixed;
            color: white;
            padding: 30px 15px;
            right: 0;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 40px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 15px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            margin-bottom: 8px;
            color: #ecfdf5;
            text-decoration: none;
            border-radius: 12px;
            transition: 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: var(--accent-green);
            transform: translateX(-5px);
        }

        .report-link { color: #fca5a5 !important; margin-top: 20px !important; }
        .report-link:hover { background: var(--warning-red) !important; color: white !important; }

        /* CONTENT */
        .main-content {
            margin-right: 260px;
            padding: 30px;
        }

        /* HEADER CARDS */
        .top-bar {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .welcome-card {
            background: var(--white);
            padding: 25px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            border-right: 8px solid var(--accent-green);
        }

        /* SEARCH AREA */
        .search-section {
            background: var(--white);
            padding: 20px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .search-input-wrapper {
            position: relative;
            display: flex;
            gap: 10px;
        }

        .search-input-wrapper input {
            flex: 1;
            padding: 15px 20px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            outline: none;
            font-size: 16px;
        }

        /* MAP BOX */
        .map-card {
            background: var(--white);
            padding: 20px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        #map { height: 400px; border-radius: 15px; }

        /* GRID SERVICES */
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .action-card {
            background: var(--white);
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            text-decoration: none;
            color: var(--text-main);
            box-shadow: var(--shadow);
            transition: 0.3s;
            border: 1px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-10px);
            border-color: var(--accent-green);
        }

        .action-card i {
            font-size: 45px;
            color: var(--accent-green);
            margin-bottom: 15px;
            display: block;
        }

        .action-card.alert i { color: var(--warning-red); }

        .badge {
            background: var(--warning-red);
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand">🌿 AgroDz البيطري</div>
    
    <a href="#" class="active"><i class="fas fa-th-large"></i> الرئيسية</a>
    <a href="{{ route('veterinaire.consultations') }}"><i class="fas fa-stethoscope"></i> الاستشارات الميدانية</a>
    <a href="{{ route('veterinaire.commandes') }}"><i class="fas fa-shopping-cart"></i> طلبات الأدوية</a>
    <a href="{{ route('veterinaire.chats') }}"><i class="fas fa-comments"></i> دردشة الفلاحين <span class="badge">3</span></a>
    <a href="{{ route('veterinaire.profile') }}"><i class="fas fa-user-md"></i> الملف الشخصي</a>
    
    <!-- ميزة التبليغ عن مرض منتشر -->
    <a href="{{ route('veterinaire.report') }}" class="report-link">
        <i class="fas fa-biohazard"></i> التبليغ عن وباء
    </a>

    <a href="{{ route('logout') }}" style="margin-top: auto;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</nav>

<div class="main-content">
    
    <!-- الترحيب والإشعارات -->
    <div class="top-bar">
        <div class="welcome-card">
            <h2>مرحباً، دكتور {{ Auth::user()->name }} 👋</h2>
            <p style="color: #64748b;">لديك اليوم 4 استشارات مجدولة وتنبيه بخصوص صحة المواشي في منطقتك.</p>
        </div>
        <div class="welcome-card" style="border-right-color: var(--warning-red);">
            <h3 style="color: var(--warning-red);"><i class="fas fa-exclamation-triangle"></i> حالة الطوارئ</h3>
            <p>تم تسجيل حالة اشتباه "حمى قلاعية" على بعد 10 كم.</p>
        </div>
    </div>

    <!-- البحث عن الأدوية عند الموزعين (حسب الـ Diagram) -->
    <div class="search-section">
        <h3><i class="fas fa-search"></i> البحث عن أدوية ومواد فلاحية</h3>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 15px;">ابحث في مخازن الموزعين المعتمدين (Distributeurs) لطلب الكميات اللازمة.</p>
        <div class="search-input-wrapper">
            <input type="text" id="med-search" placeholder="مثال: لقاح طاعون المجترات، مضادات حيوية...">
            <button class="action-card" style="padding: 10px 30px; margin: 0; background: var(--accent-green); color: white;">بحث</button>
        </div>
        <div id="search-results"></div>
    </div>

    <!-- الخريطة التفاعلية -->
    <div class="map-card">
        <h3>📍 خريطة الموزعين والنشاط الرعوي</h3>
        <div id="map"></div>
    </div>

    <!-- شبكة العمليات (Actions) -->
    <div class="actions-grid">
        <a href="{{ route('veterinaire.consultations') }}" class="action-card">
            <i class="fas fa-clipboard-list"></i>
            <h3>الاستشارات</h3>
            <p>تشخيص وعلاج حالات الفلاحين</p>
        </a>

        <a href="{{ route('veterinaire.medicines') }}" class="action-card">
            <i class="fas fa-pills"></i>
            <h3>المخزن الخاص</h3>
            <p>إدارة أدويتك المتوفرة</p>
        </a>

        <a href="{{ route('veterinaire.chats') }}" class="action-card">
            <i class="fas fa-headset"></i>
            <h3>الدعم المباشر</h3>
            <p>الإجابة على استفسارات المربين</p>
        </a>

        <a href="{{ route('veterinaire.report') }}" class="action-card alert">
            <i class="fas fa-bullhorn"></i>
            <h3>تبليغ فوري</h3>
            <p>رصد وباء أو مرض معدي</p>
        </a>
    </div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // إعدادات الخريطة (الجزائر افتراضياً)
    const lat = {{ Auth::user()->latitude ?? 36.75 }};
    const lng = {{ Auth::user()->longitude ?? 3.05 }};
    
    const map = L.map('map').setView([lat, lng], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'AgroDz Map'
    }).addTo(map);

    // موقع البيطري
    L.marker([lat, lng]).addTo(map).bindPopup("<b>عيادتك / موقعك</b>").openPopup();

    // محاكاة مواقع الموزعين (الذين يبيعون الأدوية للبيطري كما في الـ Diagram)
    const distributors = [
        { name: "موزع قالمة للأدوية", lat: 36.46, lng: 7.43 },
        { name: "شركة الأدوية الفلاحية", lat: 36.50, lng: 7.50 }
    ];

    distributors.forEach(d => {
        L.circleMarker([d.lat, d.lng], { color: 'green', radius: 8 }).addTo(map)
            .bindPopup(`<b>${d.name}</b><br>متوفر أدوية بيطرية`);
    });
</script>

</body>
</html>