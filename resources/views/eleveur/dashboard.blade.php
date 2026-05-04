<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - لوحة تحكم الفلاح</title>

    <!-- الروابط الخارجية -->
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

        /* Sidebar */
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
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 { text-align: center; font-size: 1.5rem; margin-bottom: 30px; border-bottom: 1px solid #2d6a4f; padding-bottom: 10px; }
        .sidebar a { display: block; padding: 15px 25px; color: #d1d1d1; text-decoration: none; transition: 0.3s; border-right: 4px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background: var(--primary); color: white; border-right: 4px solid var(--accent); }

        /* Content Area */
        .content { margin-right: 260px; width: calc(100% - 260px); padding: 30px; min-height: 100vh; }

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

        .section-card {
            background: var(--white);
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 25px;
        }

        #map { height: 500px; width: 100%; border-radius: 18px; border: 1.5px solid #e2e8f0; z-index: 1; }

        .btn-save {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
            width: 100%;
            justify-content: center;
            font-size: 16px;
        }

        .btn-save:hover { background: var(--secondary); transform: translateY(-2px); }

        @media (max-width: 992px) {
            .sidebar { width: 70px; }
            .sidebar h2, .sidebar a span { display: none; }
            .content { margin-right: 70px; width: calc(100% - 70px); }
        }
    </style>
</head>
<body>

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
        
        <div style="margin-top: auto; padding-bottom: 20px;">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ff8a8a;">
                <i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>

    <div class="content">
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

        <div class="section-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; color: var(--secondary);"><i class="fas fa-map-marked-alt"></i> رادار البياطرة الأقرب إليك</h3>
                <span style="font-size: 12px; background: #eee; padding: 6px 12px; border-radius: 10px; font-weight: bold;">
                    إحداثيات المزرعة: <span id="coords-display">{{ Auth::user()->latitude ?? 'لم يحدد' }}, {{ Auth::user()->longitude ?? '' }}</span>
                </span>
            </div>
            
            <div id="map"></div>
            
            <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: center;">
                <form action="{{ route('eleveur.updateLocation') }}" method="POST">
                    @csrf
                    <input type="hidden" name="lat" id="lat" value="{{ Auth::user()->latitude }}">
                    <input type="hidden" name="lng" id="lng" value="{{ Auth::user()->longitude }}">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> حفظ موقع المزرعة الجديد
                    </button>
                </form>
                <div style="font-size: 14px; color: var(--text-muted); background: #fff9f0; padding: 10px; border-radius: 10px; border-right: 4px solid var(--accent);">
                    💡 <b>نصيحة:</b> اسحب العلامة الزرقاء لوضعها فوق موقع مزرعتك، وستظهر النقاط الحمراء للبياطرة حولك تلقائياً.
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // 1. الإحداثيات الأولية
        var userLat = {{ Auth::user()->latitude ?? 36.4621 }};
        var userLng = {{ Auth::user()->longitude ?? 7.4311 }};

        var map = L.map('map').setView([userLat, userLng], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'AgroDz'
        }).addTo(map);

        var vetsLayer = L.layerGroup().addTo(map);

        var farmIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var farmMarker = L.marker([userLat, userLng], {draggable: true, icon: farmIcon}).addTo(map)
            .bindPopup("<b>موقع مزرعتك</b><br>اسحبني لتحديث البيانات")
            .openPopup();

        function updateVetRadar(lat, lng) {
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
            document.getElementById('coords-display').innerText = lat.toFixed(4) + ", " + lng.toFixed(4);

            // AJAX لجلب البياطرة
            fetch(`{{ route('eleveur.nearby.vets') }}?lat=${lat}&lng=${lng}`)
                .then(response => response.json())
                .then(vets => {
                    vetsLayer.clearLayers(); 

                    vets.forEach(vet => {
                        var vetIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        });

                        var vMarker = L.marker([vet.latitude, vet.longitude], {icon: vetIcon});
                        
                        // تصحيح: تم تغيير veterinaire إلى vet ليتوافق مع الحلقة
                        var content = `
                            <div style="text-align: right; direction: rtl; font-family: sans-serif;">
                                <strong style="color: #b91c1c;">د. ${vet.name}</strong><br>
                                <span style="font-size: 12px;">المسافة: ${parseFloat(vet.distance).toFixed(2)} كم</span><br>
                                <a href="{{ route('eleveur.consultations') }}?veterinaire_id=${vet.id}" 
                                   style="display:block; background:#2d6a4f; color:white; text-align:center; padding:6px; border-radius:5px; margin-top:8px; text-decoration:none; font-size:12px;">
                                   حجز موعد
                                </a>
                            </div>
                        `;
                        vMarker.bindPopup(content);
                        vetsLayer.addLayer(vMarker);
                    });
                });
        }

        farmMarker.on('dragend', function (e) {
            var position = farmMarker.getLatLng();
            updateVetRadar(position.lat, position.lng);
        });

        document.addEventListener('DOMContentLoaded', function() {
            updateVetRadar(userLat, userLng);
        });
    </script>
</body>
</html>