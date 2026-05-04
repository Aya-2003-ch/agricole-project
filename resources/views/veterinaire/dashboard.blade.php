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
    <a href="{{ route('veterinaire.consultations') }}" class="nav-link">
    <i class="fas fa-user-md"></i> استشارات بيطرية
    @php $count = \App\Models\Consultation::where('veterinaire_id', auth()->id())->where('status', 'pending')->count(); @endphp
    @if($count > 0)
        <span class="badge" style="background: red; color: white; border-radius: 50%; padding: 2px 6px;">{{ $count }}</span>
    @endif
</a>
    <a href="{{ route('veterinaire.commandes') }}"><i class="fas fa-shopping-basket"></i> سجل الطلبات</a>
    <a href="{{ route('veterinaire.chats') }}"><i class="fas fa-comments"></i> دردشة الفلاحين <span class="badge">3</span></a>
    <a href="{{ route('veterinaire.profile') }}"><i class="fas fa-user-md"></i> الملف الشخصي</a>
    
    <a href="{{ route('veterinaire.report') }}" class="report-link">
        <i class="fas fa-biohazard"></i> التبليغ عن وباء
    </a>

    <a href="{{ route('logout') }}" style="margin-top: auto;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</nav>

<div class="main-content">
    
    <div class="top-bar">
        <div class="welcome-card">
            <h2>مرحباً، دكتور {{ Auth::user()->name }} 👋</h2>
            <p style="color: #64748b;">لديك اليوم 4 استشارات مجدولة وتنبيه بخصوص صحة المواشي في منطقتك.</p>
        </div>
        <div class="welcome-card" style="border-right-color: #ef4444;">
            <h3 style="color: #ef4444;"><i class="fas fa-exclamation-triangle"></i> حالة الطوارئ</h3>
            <p>تم تسجيل حالة اشتباه "حمى قلاعية" على بعد 10 كم.</p>
        </div>
    </div>

    <div class="search-section" id="med-search-section">
        <h3><i class="fas fa-search"></i> نظام البحث وطلب الأدوية</h3>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 15px;">ابحث عن الأدوية والمواد البيطرية في مخازن الموزعين واطلبها لعيادتك مباشرة.</p>
        
        <form action="{{ route('veterinaire.searchMedicines') }}" method="GET" class="search-input-wrapper">
            <input type="text" name="medicine" placeholder="ابحث عن دواء (مثال: أومنيسين، لقاحات...)" value="{{ $searchQuery ?? '' }}" required>
            <button type="submit" class="action-card" style="padding: 10px 30px; margin: 0; background: #2d6a4f; color: white; cursor: pointer; border: none;">
                بحث في المخازن
            </button>
        </form>

        <div id="search-results" style="margin-top: 20px;">
            @if(isset($results))
                @if($results->isEmpty())
                    <p style="text-align: center; color: #ef4444; padding: 20px;">عذراً، هذا المنتج غير متوفر حالياً عند الموزعين المسجلين.</p>
                @else
                    <div class="results-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                        @foreach($results as $item)
                        <div class="action-card" style="text-align: right; border: 1px solid #e2e8f0; background: white; cursor: default; transition: 0.3s;">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <h4 style="margin: 0; color: #1b4332;">{{ $item->distributeur_name }}</h4>
                                <span style="background: #f0fdf4; color: #16a34a; padding: 3px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;">
                                    <i class="fas fa-truck"></i> {{ $item->distance }} كم
                                </span>
                            </div>
                            
                            <p style="font-size: 13px; color: #64748b; margin: 10px 0;"><i class="fas fa-pills"></i> المنتج: {{ $item->medicine_name }}</p>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
                                <strong style="color: #2d6a4f; font-size: 1.1rem;">{{ $item->prix }} د.ج</strong>
                                <a href="https://www.google.com/maps?q={{ $item->lat }},{{ $item->lng }}" target="_blank" style="color: #007bff; text-decoration: none; font-size: 12px;">
                                    <i class="fas fa-map-marked-alt"></i> الموقع
                                </a>
                            </div>
                            
                            <form action="{{ route('veterinaire.commandes') }}" method="POST" style="margin-top: 12px;">
                                @csrf
                                <input type="hidden" name="medicine_name" value="{{ $item->medicine_name }}">
                                <input type="hidden" name="distributeur_id" value="{{ $item->distributeur_name }}">
                                <button type="submit" style="width: 100%; padding: 10px; background: #2d6a4f; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                                    <i class="fas fa-cart-plus"></i> تأكيد طلب الشراء
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="map-card">
        <h3>📍 خريطة الموزعين والنشاط الرعوي</h3>
        <div id="map" style="height: 400px; border-radius: 15px; border: 1px solid #e2e8f0;"></div>
    </div>

    <div class="actions-grid">
        <a href="{{ route('veterinaire.consultations') }}" class="action-card">
            <i class="fas fa-clipboard-list" style="color: #2d6a4f;"></i>
            <h3>الاستشارات</h3>
            <p>إدارة مواعيد الكشوفات الميدانية</p>
        </a>

        <a href="{{ route('veterinaire.chats') }}" class="action-card">
            <i class="fas fa-headset" style="color: #007bff;"></i>
            <h3>الدعم الفني</h3>
            <p>تواصل مباشر مع المربين</p>
        </a>

        <a href="{{ route('veterinaire.report') }}" class="action-card alert" style="background: #fff5f5;">
            <i class="fas fa-bullhorn" style="color: #ef4444;"></i>
            <h3 style="color: #ef4444;">تبليغ عن وباء</h3>
            <p>إخطار المصالح الفلاحية فوراً</p>
        </a>
    </div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    const lat = {{ Auth::user()->latitude ?? 36.4621 }};
    const lng = {{ Auth::user()->longitude ?? 7.4311 }};
    
    const map = L.map('map').setView([lat, lng], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; AgroDz'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map).bindPopup("<b>دكتور {{ Auth::user()->name }}</b>").openPopup();

    @if(isset($results))
        const searchResults = @json($results);
        searchResults.forEach(res => {
            L.marker([res.lat, res.lng])
              .addTo(map)
              .bindPopup(`<b>${res.distributeur_name}</b><br>متوفر: ${res.medicine_name}<br>السعر: ${res.price} د.ج`);
        });
    @endif
</script>

</body>
</html>