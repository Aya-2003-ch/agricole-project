<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الموزع | AgroDz</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --primary-dark: #1e2d24; 
            --primary-green: #588157;
            --light-bg: #f4f7f6;
            --white: #ffffff;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Tajawal', sans-serif; }
        body { background-color: var(--light-bg); display: flex; }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width); height: 100vh;
            background: linear-gradient(180deg, var(--primary-dark), #1b2e25);
            position: fixed; right: 0; color: white; padding: 30px 15px;
            box-shadow: -5px 0 15px rgba(0,0,0,0.1); z-index: 1000;
        }
        .sidebar-brand {
            font-size: 26px; font-weight: 700; text-align: center; margin-bottom: 50px;
            display: flex; align-items: center; justify-content: center; gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px;
        }
        .sidebar a {
            display: flex; align-items: center; gap: 12px; padding: 14px 20px;
            margin-bottom: 10px; color: #dad7cd; text-decoration: none;
            border-radius: 12px; transition: all 0.3s ease;
        }
        .sidebar a:hover, .sidebar a.active { background: var(--primary-green); color: white; transform: translateX(-5px); }
        
        .logout-btn { 
            margin-top: 30px; 
            background: rgba(231, 76, 60, 0.1) !important; 
            color: #e74c3c !important; 
            border: none;
            width: 100%;
            cursor: pointer;
        }

        .content { margin-right: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); padding: 40px; }

        /* --- SEARCH SECTION --- */
        .search-section {
            background: var(--white); padding: 30px; border-radius: 20px;
            margin-bottom: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border-top: 5px solid var(--primary-green);
        }
        .search-group { display: flex; gap: 10px; margin-top: 15px; position: relative; }
        .search-input-wrapper { position: relative; flex-grow: 1; }
        .search-input-wrapper i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--primary-green); }
        .search-input-wrapper input {
            width: 100%; padding: 15px 45px 15px 15px; border: 2px solid #f1f3f5;
            border-radius: 12px; outline: none; transition: 0.3s; font-size: 16px;
        }
        .btn-search { background: var(--primary-green); color: white; border: none; padding: 0 30px; border-radius: 12px; cursor: pointer; font-weight: bold; }

        .suggestions-list {
            position: absolute; top: 100%; right: 0; left: 0;
            background: white; border: 1px solid #ddd; border-radius: 0 0 12px 12px;
            z-index: 2000; list-style: none; max-height: 200px; overflow-y: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: none;
        }
        .suggestions-list li { padding: 12px 15px; cursor: pointer; border-bottom: 1px solid #eee; text-align: right; }

        /* --- STAT CARDS --- */
        .cards-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .stat-card { background: var(--white); padding: 25px; border-radius: 20px; display: flex; align-items: center; gap: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.03); border: 1px solid #eee; }
        .card-icon { width: 50px; height: 50px; background: #f0f4f2; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--primary-green); }
        
        .map-card { background: var(--white); padding: 20px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-top: 35px; }
        #map { height: 400px; width: 100%; border-radius: 15px; z-index: 1; }
    </style>
</head>

<body>

    <nav class="sidebar">
        <div class="sidebar-brand"><i class="fas fa-seedling"></i> <span>AgroDz</span></div>
        <a href="{{ route('distributeur.dashboard') }}" class="active"><i class="fas fa-th-large"></i> <span>الرئيسية</span></a>
        <a href="{{ route('produits.index') }}"><i class="fas fa-box-open"></i> <span>منتجاتي</span></a>
        <a href="{{ route('distributeur.incoming.orders') }}"><i class="fas fa-hand-holding-medical"></i> <span>الطلبات الواردة</span></a>
        <a href="{{ route('distributeur.profile') }}"><i class="fas fa-user-circle"></i> <span>الملف الشخصي</span></a>
        <a href="{{ route('distributeur.my_orders') }}"><i class="fas fa-shopping-cart"></i> <span>طلباتي</span></a>
        
        <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> <span>خروج</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </nav>

    <main class="content">
        <div class="search-section">
            <h2 style="color: var(--primary-dark);">مرحباً، {{ Auth::user()->name }} 👋</h2>
            <p style="color: #636e72;">ابحث عن الأدوية والمنتجات المتوفرة في سوق الموزعين الآن.</p>
            
            <form action="{{ route('distributeur.market') }}" method="GET" class="search-group" id="searchForm">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="query" id="productSearch" placeholder="ابحث عن دواء..." autocomplete="off">
                    <ul id="suggestions" class="suggestions-list"></ul>
                </div>
                <button type="submit" class="btn-search">بحث سريع</button>
            </form>
        </div>

        <div class="cards-container">
            <div class="stat-card">
                <div class="card-icon"><i class="fas fa-boxes-stacked"></i></div>
                <div class="stat-info">
                    <h3 style="font-size: 13px; color: #b2bec3;">إجمالي منتجاتي</h3> 
                    <div style="font-size: 22px; font-weight: 700;">{{ $totalProduits }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="card-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="stat-info">
                    <h3 style="font-size: 13px; color: #b2bec3;">الموزعون المسجلون</h3>
                    <div style="font-size: 22px; font-weight: 700;">{{ count($allDistributors) }}</div>
                </div>
            </div>
        </div>

        <div class="map-card">
            <div style="margin-bottom: 15px; font-weight: bold;"><i class="fas fa-globe-africa"></i> خريطة الموزعين المسجلين في AgroDz</div>
            <div id="map"></div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        $(document).ready(function() {
            // منطق شريط البحث
            $('#productSearch').on('keyup', function() {
                let term = $(this).val();
                if (term.length >= 2) {
                    $.ajax({
                        url: "{{ route('distributeur.suggestions') }}", 
                        method: "GET",
                        data: { term: term },
                        success: function(data) {
                            let list = $('#suggestions');
                            list.empty().show();
                            data.forEach(item => list.append(`<li>${item}</li>`));
                        }
                    });
                } else { $('#suggestions').hide(); }
            });

            $(document).on('click', '#suggestions li', function() {
                $('#productSearch').val($(this).text());
                $('#suggestions').hide();
                $('#searchForm').submit();
            });
        });

        // --- إعداد الخريطة مع الإحداثيات المصححة ---
        var map = L.map('map').setView([36.7525, 3.0420], 6); 
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var locations = @json($allDistributors);
        var markersGroup = L.featureGroup();

        locations.forEach(function(dist) {
            // استخدامlatitude و longitude كما هما في جدولك
            var lat = parseFloat(dist.latitude);
            var lng = parseFloat(dist.longitude);

            if(!isNaN(lat) && !isNaN(lng) && lat !== 0) {
                var marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup(`
                        <div style="text-align: right; direction: rtl;">
                            <b style="color:var(--primary-green);">${dist.nom || dist.name}</b><br>
                            <span style="font-size:12px;">${dist.address || 'العنوان غير متوفر'}</span>
                        </div>
                    `);
                markersGroup.addLayer(marker);
            }
        });

        if (markersGroup.getLayers().length > 0) {
            map.fitBounds(markersGroup.getBounds().pad(0.5));
        }
    </script>
</body>
</html>