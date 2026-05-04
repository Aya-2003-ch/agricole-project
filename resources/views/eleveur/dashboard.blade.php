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
            --danger: #ef4444;
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

        .search-box { display: flex; gap: 10px; }
        .search-box input {
            flex: 1;
            padding: 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            outline: none;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn-primary {
            padding: 0 35px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary:hover { background: var(--secondary); transform: translateY(-2px); }

        /* Map */
        #map { height: 500px; width: 100%; border-radius: 18px; border: 1.5px solid #e2e8f0; z-index: 1; }

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
        <a href="{{ route('eleveur.dashboard') }}" class="active">
            <i class="fas fa-home"></i> <span>الرئيسية</span>
        </a>
        <a href="{{ route('eleveur.consultations') }}">
            <i class="fas fa-user-md"></i> <span>استشارة بيطرية</span>
        </a>
        <a href="{{ route('eleveur.chats') }}">
            <i class="fas fa-comments"></i> <span>المحادثات</span>
        </a>
        
        <div style="margin-top: auto; border-top: 1px solid #2d6a4f; padding-top: 10px;">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ff8a8a;">
                <i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">

        <!-- Header -->
        <div class="header">
            <div class="welcome-msg">
                <h2>أهلاً بك، {{ Auth::user()->name }} 👨‍🌾</h2>
                <p>استكشف البياطرة الأقرب إليك وتابع حالة مزرعتك.</p>
            </div>
            
            <div style="font-size: 1.5rem; color: var(--primary); position: relative; cursor: pointer;">
                <i class="fas fa-bell"></i>
                <span style="position: absolute; top: -5px; right: -8px; background: var(--danger); color: white; font-size: 10px; padding: 2px 6px; border-radius: 50%; border: 2px solid white;">2</span>
            </div>
        </div>

        <!-- قسم الخريطة التفاعلية -->
        <div class="section-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; color: var(--secondary);"><i class="fas fa-map-marked-alt"></i> رادار البياطرة الأقرب إليك</h3>
                <span style="font-size: 12px; background: #eee; padding: 4px 10px; border-radius: 10px;">
                    إحداثيات المزرعة: <span id="coords-display">{{ Auth::user()->latitude ?? 'لم يحدد' }}, {{ Auth::user()->longitude ?? '' }}</span>
                </span>
            </div>
            
            <div id="map"></div>
            
            <div style="margin-top: 15px; display: flex; gap: 10px; align-items: center;">
                <form action="{{ route('eleveur.updateLocation') }}" method="POST" style="flex: 1;">
                    @csrf
                    <input type="hidden" name="lat" id="lat" value="{{ Auth::user()->latitude }}">
                    <input type="hidden" name="lng" id="lng" value="{{ Auth::user()->longitude }}">
                    <button type="submit" class="btn-primary" style="background: var(--accent); width: 100%; height: 50px;">
                        <i class="fas fa-save"></i> حفظ موقع المزرعة الجديد رسمياً
                    </button>
                </form>
                <p style="font-size: 13px; color: var(--text-muted); flex: 1;">
                    💡 <b>نصيحة:</b> اسحب العلامة الزرقاء لوضعها فوق موقع مزرعتك بالضبط، وستظهر النقاط الحمراء (البياطرة) حولك تلقائياً.
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // 1. الإحداثيات الأولية (من قاعدة البيانات أو افتراضية)
        var userLat = {{ Auth::user()->latitude ?? 36.4621 }};
        var userLng = {{ Auth::user()->longitude ?? 7.4311 }};

        var map = L.map('map').setView([userLat, userLng], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'AgroDz Map'
        }).addTo(map);

        // طبقة خاصة لماركرات البياطرة لتسهيل مسحها
        var vetsLayer = L.layerGroup().addTo(map);

        // 2. ماركر المزرعة (أزرق وقابل للسحب)
        var farmIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var farmMarker = L.marker([userLat, userLng], {draggable: true, icon: farmIcon}).addTo(map)
            .bindPopup("<b>موقع مزرعتك</b><br>اسحبني لتحديث رادار البياطرة")
            .openPopup();

        // 3. دالة جلب البياطرة "الرادار"
        function updateVetRadar(lat, lng) {
            // تحديث الواجهة
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
            document.getElementById('coords-display').innerText = lat.toFixed(4) + ", " + lng.toFixed(4);

            // AJAX لجلب البياطرة القريبين من الموقع الجديد
            fetch(`{{ route('eleveur.nearby.vets') }}?lat=${lat}&lng=${lng}`)
                .then(response => response.json())
                .then(vets => {
                    vetsLayer.clearLayers(); // مسح النقاط القديمة

                    vets.forEach(vet => {
                        // أيقونة حمراء للبياطرة
                        var vetIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        });

                        var vMarker = L.marker([vet.latitude, vet.longitude], {icon: vetIcon});
                        
                        var content = `
                            <div style="text-align: right; direction: rtl;">
                                <strong style="color: #b91c1c;">د. ${vet.name}</strong><br>
                                <small>المسافة: ${parseFloat(vet.distance).toFixed(2)} كم</small><br>
                                <a href="{{ route('eleveur.consultations') }}?vet_id=${vet.id}" 
                                   style="display:block; background:#2d6a4f; color:white; text-align:center; padding:5px; border-radius:5px; margin-top:8px; text-decoration:none; font-size:11px;">
                                   طلب استشارة
                                </a>
                            </div>
                        `;
                        vMarker.bindPopup(content);
                        vetsLayer.addLayer(vMarker);
                    });
                });
        }

        // حدث عند سحب ماركر المزرعة
        farmMarker.on('dragend', function (e) {
            var position = farmMarker.getLatLng();
            updateVetRadar(position.lat, position.lng);
        });

        // تشغيل الرادار عند تحميل الصفحة لأول مرة
        document.addEventListener('DOMContentLoaded', function() {
            updateVetRadar(userLat, userLng);
        });
    </script>
</body>
</html>