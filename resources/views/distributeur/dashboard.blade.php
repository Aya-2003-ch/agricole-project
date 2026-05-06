<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الموزع | AgroDz</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS للخرائط -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --primary-dark: #344e41; 
            --primary-green: #588157;
            --accent-green: #a3b18a;
            --light-bg: #f4f7f6;
            --white: #ffffff;
            --text-main: #2d3436;
            --sidebar-width: 260px;
        }

        /* تنسيقات إضافية لقائمة الاقتراحات */
        .suggestions-list {
            position: absolute;
            top: 100%;
            right: 0;
            left: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0 0 12px 12px;
            z-index: 2000;
            list-style: none;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            display: none;
        }
        .suggestions-list li {
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            text-align: right;
        }
        .suggestions-list li:hover { background-color: #f8f9fa; color: var(--primary-green); }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Tajawal', sans-serif; }
        body { background-color: var(--light-bg); color: var(--text-main); display: flex; }

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
        .nav-badge { background: #e74c3c; color: white; font-size: 10px; padding: 2px 8px; border-radius: 10px; margin-right: auto; }
        .logout-btn { margin-top: 30px; background: rgba(231, 76, 60, 0.1) !important; color: #e74c3c !important; }
        
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

        /* --- MAP & STATS --- */
        .map-card { background: var(--white); padding: 20px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-top: 35px; }
        #map { height: 400px; width: 100%; border-radius: 15px; z-index: 1; }
        .cards-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .stat-card { background: var(--white); padding: 25px; border-radius: 20px; display: flex; align-items: center; gap: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.03); }
        .card-icon { width: 50px; height: 50px; background: #f0f4f2; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--primary-green); }
    </style>
</head>

<body>

    <nav class="sidebar">
        <div class="sidebar-brand"><i class="fas fa-seedling"></i> <span>AgroDz</span></div>
        <a href="{{ route('distributeur.dashboard') }}" class="active"><i class="fas fa-th-large"></i> <span>الرئيسية</span></a>
        <a href="{{ route('produits.index') }}"><i class="fas fa-box-open"></i> <span>Mes Produits</span></a>
        <a href="{{ route('distributeur.incoming.orders') }}">
            <i class="fas fa-hand-holding-medical"></i> <span>Commandes Reçues</span>
            <span class="nav-badge">3</span>
        </a>
        <a href="{{ route('distributeur.profile') }}"><i class="fas fa-user-circle"></i> <span>Profil</span></a>
        <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </nav>

    <main class="content">
        <div class="search-section">
            <h2 style="color: var(--primary-dark);">مرحباً، {{ Auth::user()->name }} 👋</h2>
            <p style="color: #636e72;">ابحث عن الأدوية والمنتجات المتوفرة في سوق الموزعين الآن.</p>
            
            <!-- نموذج البحث مع الاقتراحات التلقائية -->
            <form action="{{ route('distributeur.market') }}" method="GET" class="search-group" id="searchForm">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="query" id="productSearch" placeholder="ابحث عن دواء..." autocomplete="off">
                    <!-- قائمة الاقتراحات -->
                    <ul id="suggestions" class="suggestions-list"></ul>
                </div>
                <button type="submit" class="btn-search">Rechercher</button>
            </form>
        </div>

        <div class="cards-container">
            <div class="stat-card">
                <div class="card-icon"><i class="fas fa-boxes-stacked"></i></div>
                <div class="stat-info">
                    <h3 style="font-size: 13px; color: #b2bec3;">Mes Produits</h3> 
                    <div style="font-size: 22px; font-weight: 700;">{{ $totalProduits }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="card-icon"><i class="fas fa-file-import"></i></div>
                <div class="stat-info">
                    <h3 style="font-size: 13px; color: #b2bec3;">Commandes Reçues</h3>
                    <div style="font-size: 22px; font-weight: 700;">3</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="card-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="stat-info">
                    <h3 style="font-size: 13px; color: #b2bec3;">Distributeurs</h3>
                    <div style="font-size: 22px; font-weight: 700;">{{ count($allDistributors) }}</div>
                </div>
            </div>
        </div>

        <div class="map-card">
            <div style="margin-bottom: 15px; font-weight: bold;"><i class="fas fa-globe-africa"></i> خريطة الموزعين المسجلين</div>
            <div id="map"></div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#productSearch').on('keyup', function() {
            let term = $(this).val();
            if (term.length >= 2) {
                $.ajax({
                    // استخدام الـ route الصحيح الذي عرفناه في web.php
                    url: "{{ route('distributeur.suggestions') }}", 
                    method: "GET",
                    data: { term: term },
                    success: function(data) {
                        let list = $('#suggestions');
                        list.empty().show();
                        if(data.length > 0) {
                            data.forEach(function(item) {
                                list.append(`<li>${item}</li>`);
                            });
                        } else {
                            list.hide();
                        }
                    }
                });
            } else {
                $('#suggestions').hide();
            }
        });

        $(document).on('click', '#suggestions li', function() {
            $('#productSearch').val($(this).text());
            $('#suggestions').hide();
            $('#searchForm').submit();
        });
    });

    // --- تصحيح الخريطة ---
    var map = L.map('map').setView([36.7525, 3.0420], 6); 
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    var locations = @json($allDistributors);
    locations.forEach(function(dist) {
        if(dist.lat && dist.lng) {
            // التعديل هنا: استخدمنا الأسماء التي أرسلتها من الـ Controller
            L.marker([dist.lat, dist.lng]).addTo(map)
                .bindPopup(`<b>${dist.name}</b><br>${dist.address}`);
        }
    });
</script>
</body>
</html>