<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - لوحة تحكم الفلاح</title>

    <!-- الروابط الخارجية: الخرائط والأيقونات -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary: #2d6a4f; 
            --secondary: #1b4332;
            --accent: #d4a373;
            --bg: #f8f9fa;
            --white: #ffffff;
            --text-muted: #64748b;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            display: flex;
            color: #334155;
        }

        /* Sidebar - القائمة الجانبية */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--secondary);
            color: white;
            position: fixed;
            right: 0; 
            padding: 20px 0;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .sidebar h2 { text-align: center; font-size: 1.5rem; margin-bottom: 30px; border-bottom: 1px solid #2d6a4f; padding-bottom: 10px; }
        .sidebar a { display: block; padding: 15px 25px; color: #d1d1d1; text-decoration: none; transition: 0.3s; border-right: 4px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background: var(--primary); color: white; border-right: 4px solid var(--accent); }

        /* Main Content - المحتوى الرئيسي */
        .content { margin-right: 260px; width: calc(100% - 260px); padding: 30px; }

        /* Header - رأس الصفحة */
        .header {
            background: var(--white);
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-msg h2 { margin: 0; color: var(--secondary); font-size: 24px; }
        .welcome-msg p { margin: 5px 0 0 0; color: var(--text-muted); }

        /* Cards & Sections - الأقسام */
        .section-card {
            background: var(--white);
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 25px;
        }

        /* Search - نظام البحث */
        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            outline: none;
            font-size: 16px;
            transition: 0.3s;
        }

        .search-box input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1); }

        .btn-primary {
            padding: 0 35px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-primary:hover { background: var(--secondary); transform: translateY(-2px); }

        /* Results Grid - شبكة النتائج */
        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .result-card {
            background: var(--white);
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 18px;
            position: relative;
            transition: 0.3s;
        }

        .result-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }

        .distance-badge {
            position: absolute; left: 15px; top: 15px;
            background: #f0fdf4; color: #16a34a;
            padding: 5px 12px; border-radius: 20px;
            font-size: 12px; font-weight: bold;
        }

        .price-tag {
            color: var(--primary);
            font-size: 20px;
            font-weight: 800;
        }

        /* Map - الخريطة */
        #map { height: 350px; width: 100%; border-radius: 18px; border: 1.5px solid #e2e8f0; }

        @media (max-width: 992px) {
            .sidebar { width: 70px; }
            .sidebar h2, .sidebar a span { display: none; }
            .content { margin-right: 70px; width: calc(100% - 70px); }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-tractor"></i> AgroDz</h2>
        <a href="#" class="active"><i class="fas fa-home"></i> <span>الرئيسية</span></a>
        <a href="#"><i class="fas fa-pills"></i> <span>الأدوية والموزعين</span></a>
        <a href="#"><i class="fas fa-user-md"></i> <span>استشارة بيطرية</span></a>
        <a href="#"><i class="fas fa-comments"></i> <span>المحادثات</span></a>
        <a href="#"><i class="fas fa-shopping-basket"></i> <span>طلباتي</span></a>
        <div style="margin-top: auto; border-top: 1px solid #2d6a4f; padding-top: 10px;">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ff8a8a;">
            <i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    </div>

    <!-- Main Content -->
    <div class="content">

        <!-- Header -->
        <div class="header">
            <div class="welcome-msg">
                <h2>أهلاً بك، {{ Auth::user()->name }} 👨‍🌾</h2>
                <p>إليك قائمة الموزعين الأقرب لمزرعتك في ولاية قالمة.</p>
            </div>
            
            <div style="font-size: 1.5rem; color: var(--primary); position: relative; cursor: pointer;">
                <i class="fas fa-bell"></i>
                <span style="position: absolute; top: -5px; right: -8px; background: #ef4444; color: white; font-size: 10px; padding: 2px 6px; border-radius: 50%; border: 2px solid white;">2</span>
            </div>
        </div>

        <!-- Search Section - قسم البحث -->
        <div class="section-card">
            <h3 style="margin-bottom: 20px; color: var(--secondary);"><i class="fas fa-search-location"></i> ابحث عن دواء (نتائج حسب الأقرب والسعر)</h3>
            <form action="{{ route('eleveur.search') }}" method="GET" class="search-box">
                <input type="text" name="medicine" placeholder="مثلاً: أدويـة أبقـار، لقاحات، Vitamine..." required>
                <button type="submit" class="btn-primary">بحث سريع</button>
            </form>
        </div>

        <!-- Results Section - نتائج البحث (تظهر عند الضغط على بحث) -->
        @if(isset($results))
        <div class="results-grid">
            @foreach($results as $item)
            <div class="result-card">
                <div class="distance-badge"><i class="fas fa-map-marker-alt"></i> يبعد {{ $item->distance }} كم</div>
                <h4 style="margin: 30px 0 10px 0; color: var(--secondary);">{{ $item->distributeur_name }}</h4>
                <p style="font-size: 14px; color: var(--text-muted);"><i class="fas fa-store"></i> المتوفر: {{ $item->medicine_name }}</p>
                <p style="font-size: 14px; color: var(--text-muted);"><i class="fas fa-map-pin"></i> {{ $item->address }}</p>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                    <span class="price-tag">{{ $item->prix }} د.ج</span>
                    <a href="https://www.google.com/maps?q={{ $item->lat }},{{ $item->lng }}" target="_blank" style="color: #007bff; text-decoration: none; font-size: 13px; font-weight: bold;">
                        <i class="fas fa-directions"></i> الاتجاهات
                    </a>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button class="btn-primary" style="flex: 2; font-size: 13px;"><i class="fas fa-shopping-cart"></i> طلب المنتج</button>
                    <button class="btn-primary" style="flex: 1; background: #e9f5ff; color: #007bff;"><i class="fas fa-comment"></i></button>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Map Section - موقع المزرعة -->
        <div class="section-card" style="margin-top: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; color: var(--secondary);"><i class="fas fa-map-marked-alt"></i> موقع مزرعتك المسجل</h3>
                <span style="font-size: 12px; background: #eee; padding: 4px 10px; border-radius: 10px;">إحداثياتك: {{ Auth::user()->latitude ?? '36.46' }}, {{ Auth::user()->longitude ?? '7.43' }}</span>
            </div>
            
            <div id="map"></div>
            
            <form action="{{ route('eleveur.updateLocation') }}" method="POST" style="margin-top: 15px;">
                @csrf
                <input type="hidden" name="lat" id="lat" value="{{ Auth::user()->latitude }}">
                <input type="hidden" name="lng" id="lng" value="{{ Auth::user()->longitude }}">
                <button type="submit" class="btn-primary" style="background: var(--accent);">
                    <i class="fas fa-save"></i> حفظ الموقع الجديد للمزرعة
                </button>
            </form>
        </div>

    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // جلب إحداثيات المستخدم من قاعدة البيانات
        var userLat = {{ Auth::user()->latitude ?? 36.4621 }};
        var userLng = {{ Auth::user()->longitude ?? 7.4311 }};

        // إعداد الخريطة
        var map = L.map('map').setView([userLat, userLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'AgroDz Map'
        }).addTo(map);

        // إضافة ماركر قابل للسحب لتحديد الموقع
        var marker = L.marker([userLat, userLng], {draggable: true}).addTo(map)
            .bindPopup("موقع مزرعتك")
            .openPopup();

        // تحديث الإحداثيات عند سحب الماركر
        marker.on('dragend', function (e) {
            var lat = marker.getLatLng().lat;
            var lng = marker.getLatLng().lng;
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
        });

        // تحديث الإحداثيات عند النقر على الخريطة
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
        });
    </script>
</body>
</html>