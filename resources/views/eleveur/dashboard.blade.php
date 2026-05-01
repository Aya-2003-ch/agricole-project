<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الفلاح - PFE</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary: #2d6a4f; 
            --secondary: #1b4332;
            --bg: #f8f9fa;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            display: flex;
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
        }

        .sidebar h2 { text-align: center; font-size: 1.5rem; margin-bottom: 30px; border-bottom: 1px solid #2d6a4f; padding-bottom: 10px; }
        .sidebar a { display: block; padding: 15px 25px; color: #d1d1d1; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover { background: var(--primary); color: white; padding-right: 35px; }

        /* Content */
        .content { margin-right: 260px; width: calc(100% - 260px); padding: 30px; }

        /* Header مع الجرس */
        .header {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* ستايل الجرس (Notification) */
        .notif-wrapper { position: relative; cursor: pointer; }
        .notif-bell { font-size: 1.5rem; color: var(--primary); position: relative; }
        .notif-count {
            position: absolute; top: -5px; right: -10px;
            background: #ff4444; color: white; font-size: 0.7rem;
            padding: 2px 6px; border-radius: 50%; font-weight: bold; border: 2px solid white;
        }
        .notif-dropdown {
            position: absolute; top: 45px; left: 0; width: 300px;
            background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            display: none; z-index: 1000; overflow: hidden;
        }
        .notif-dropdown.show { display: block; }
        .notif-header { background: #f8f9fa; padding: 10px 15px; font-weight: bold; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; }
        .notif-item { padding: 12px 15px; border-bottom: 1px solid #f1f1f1; font-size: 0.9rem; transition: 0.3s; }
        .notif-item:hover { background: #f0f7ff; }

        /* Cards & Search */
        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; border-radius: 15px; display: flex; align-items: center; gap: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-bottom: 4px solid var(--primary); }
        .card i { font-size: 2rem; color: var(--primary); }

        .search-box { background: white; padding: 10px; border-radius: 10px; display: flex; gap: 10px; margin-bottom: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .search-box input { flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 8px; }
        .search-box button { padding: 0 25px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: bold; }

        /* Map */
        #map { height: 350px; width: 100%; border-radius: 10px; border: 1px solid #ddd; }
        .map-section { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .save-btn { background: var(--primary); color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2><i class="fas fa-tractor"></i> منصة الفلاح</h2>
        <a href="#"><i class="fas fa-home"></i> الرئيسية</a>
        <a href="#"><i class="fas fa-box"></i> طلبياتي</a>
        <a href="#"><i class="fas fa-user-md"></i> الأطباء البياطرة</a>
        <a href="#"><i class="fas fa-store"></i> الموزعين</a>
    </div>

    <div class="content">

        <div class="header">
            <div>
                <h2>مرحبا بك يافلاح  👨‍🌾</h2>
                <p>تتبع حالة مزرعتك وتواصل مع المتخصصين</p>
            </div>
            
            <div class="notif-wrapper" onclick="toggleNotifs()">
                <div class="notif-bell">
                    <i class="fas fa-bell"></i>
                    <span class="notif-count">2</span>
                </div>
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header"><span>التنبيهات</span></div>
                    <div class="notif-item"><i class="fas fa-syringe" style="color: #ffc107"></i> انتشار مرض في المنطقة</div>
                    <div class="notif-item"><i class="fas fa-check-circle" style="color: #28a745"></i>  📦 نقص في دواء معين</div>
                    <div style="text-align: center; padding: 10px; background: #f8f9fa;"><a href="#" style="color: var(--primary); font-size: 0.8rem; text-decoration: none;">مشاهدة الكل</a></div>
                </div>
            </div>
        </div>

        <div class="cards">
            <div class="card"><i class="fas fa-clipboard-list"></i><div><h3>الطلبات</h3><p>05</p></div></div>
            <div class="card"><i class="fas fa-stethoscope"></i><div><h3>بياطرة</h3><p>03</p></div></div>
            <div class="card"><i class="fas fa-truck-moving"></i><div><h3>موزعين</h3><p>04</p></div></div>
        </div>

        <div class="search-box">
            <input type="text" placeholder="ابحث عن طبيب أو موزع في قالمة...">
            <button><i class="fas fa-search"></i> بحث</button>
        </div>

        <div class="map-section">
            <h3 style="margin-bottom: 15px;"><i class="fas fa-map-marked-alt"></i> تحديد موقع المزرعة</h3>
            <div id="map"></div>
            
            <form action="{{ route('eleveur.store') }}" method="POST">
                @csrf
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <button type="submit" class="save-btn"><i class="fas fa-save"></i> حفظ موقع المزرعة</button>
            </form>
        </div>

    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // 1. تشغيل الخريطة
        var map = L.map('map').setView([36.4621, 7.4311], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        var marker;
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });

        // 2. تشغيل الجرس
        function toggleNotifs() {
            document.getElementById('notifDropdown').classList.toggle('show');
        }
        window.onclick = function(event) {
            if (!event.target.matches('.notif-bell, .notif-bell *')) {
                var dropdown = document.getElementById('notifDropdown');
                if (dropdown.classList.contains('show')) dropdown.classList.remove('show');
            }
        }
    </script>
</body>
</html>