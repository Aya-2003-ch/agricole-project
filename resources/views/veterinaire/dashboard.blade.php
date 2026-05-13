<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - لوحة البيطري</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

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
            text-align: right;
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
            z-index: 1000;
        }

        .sidebar-brand {
            text-align: center; font-size: 24px; font-weight: bold;
            margin-bottom: 40px; border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 15px;
        }

        .sidebar a {
            display: flex; align-items: center; justify-content: space-between; padding: 14px;
            margin-bottom: 8px; color: #ecfdf5; text-decoration: none;
            border-radius: 12px; transition: 0.3s;
        }
        .sidebar a div { display: flex; align-items: center; gap: 12px; }

        .sidebar a:hover, .sidebar a.active {
            background: var(--accent-green); transform: translateX(-5px); color: white;
        }

        .main-content { margin-right: 260px; padding: 30px; }

        /* NOTIFICATIONS DROPDOWN */
        .notifications-dropdown { position: relative; cursor: pointer; }
        .notification-icon-wrapper {
            width: 50px; height: 50px; background: var(--white); border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: var(--shadow); transition: all 0.3s ease;
        }
        .notification-icon-wrapper:hover { background-color: #fff1f1 !important; transform: translateY(-2px); }
        
        .dropdown-menu-custom {
            display: none; position: absolute; left: 0; top: 60px; background: white;
            min-width: 320px; box-shadow: 0px 10px 25px rgba(0,0,0,0.1); border-radius: 15px;
            z-index: 2000; max-height: 400px; overflow-y: auto; border: 1px solid #eee;
        }
        .dropdown-menu-custom.show { display: block; }
        .notif-header { padding: 15px; font-weight: bold; background: #f8f9fa; border-bottom: 1px solid #eee; border-radius: 15px 15px 0 0; }
        .notif-item {
            color: #333; padding: 15px; text-decoration: none; display: flex; gap: 12px;
            border-bottom: 1px solid #f8f9fa; font-size: 13px; transition: 0.2s;
        }
        .notif-item:hover { background: #f9f9f9; color: var(--accent-green); }
        
        .anim-pulse { animation: pulse-red 2s infinite; }
        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
        }

        /* SEARCH SECTION */
        .search-container {
            background: var(--white); padding: 30px; border-radius: 20px;
            margin-bottom: 30px; box-shadow: var(--shadow); text-align: center; border: 1px solid #e2e8f0;
        }
        .search-bar-custom {
            background: #f1f5f9; border-radius: 15px; padding: 8px;
            display: flex; max-width: 800px; margin: 20px auto 0; border: 1px solid #cbd5e1;
        }
        .search-bar-custom input {
            border: none; flex: 1; padding: 10px 20px; outline: none;
            font-size: 16px; border-radius: 15px; background: transparent;
        }
        .search-bar-custom button {
            background: var(--accent-green); color: white; border: none;
            padding: 10px 30px; border-radius: 12px; font-weight: bold; cursor: pointer;
        }

        #map { height: 400px; border-radius: 20px; border: 1px solid #e2e8f0; }
        .medicine-card { border: none; border-radius: 18px; transition: 0.3s; background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .medicine-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand">🌿 AgroDz البيطري</div>
    
    <a href="{{ route('veterinaire.dashboard') }}" class="active">
        <div><i class="fas fa-th-large"></i> الرئيسية</div>
    </a>
    
    <a href="{{ route('veterinaire.consultations') }}">
        <div><i class="fas fa-stethoscope"></i> الاستشارات</div>
        @php
            $count = \App\Models\Consultation::where('veterinaire_id', Auth::id())->where('status', 'pending')->count();
        @endphp
        @if($count > 0)
            <span class="badge bg-danger rounded-pill">{{ $count }}</span>
        @endif
    </a>

    <a href="{{ route('veterinaire.my_orders') }}">
        <div><i class="fas fa-shopping-basket"></i> سجل الطلبات</div>
        @if(isset($orderNotifications) && $orderNotifications > 0)
            <span class="badge bg-danger rounded-pill">{{ $orderNotifications }}</span>
        @endif
    </a>

    <a href="{{ route('veterinaire.chats') }}">
        <div><i class="fas fa-comments"></i> الدردشة</div>
    </a>
    
    <a href="{{ route('veterinaire.profile') }}">
        <div><i class="fas fa-user-md"></i> الملف الشخصي</div>
    </a>
    
    <a href="{{ route('veterinaire.report') }}" class="text-danger mt-4 fw-bold">
        <div><i class="fas fa-biohazard"></i> التبليغ عن وباء</div>
    </a>

    <a href="{{ route('logout') }}" style="margin-top: auto;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <div><i class="fas fa-sign-out-alt"></i> خروج</div>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</nav>

<div class="main-content">
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="p-4 bg-white rounded-4 shadow-sm border-end border-5 border-success d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold">مرحباً، دكتور {{ Auth::user()->name }} 👋</h2>
                    <p class="text-muted mb-0">تابع حالة الأوبئة، تواصل مع الموزعين، واستقبل طلبات الفلاحين.</p>
                </div>

                <!-- نظام التنبيهات المنسدل للأوبئة -->
                <div class="notifications-dropdown" onclick="toggleNotifs()">
                    <div class="notification-icon-wrapper">
                        <i class="fas fa-biohazard text-danger fs-4"></i>
                        @if(isset($unreadReportsCount) && $unreadReportsCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger anim-pulse" style="font-size: 11px;">
                                {{ $unreadReportsCount }}
                            </span>
                        @endif
                    </div>
                    
                    <div id="notif-menu" class="dropdown-menu-custom">
                        <div class="notif-header">بلاغات الأوبئة الأخيرة</div>
                        @if(isset($latestReports) && count($latestReports) > 0)
                            @foreach($latestReports as $report)
                                <a href="{{ route('veterinaire.epidemic.reports.index') }}" class="notif-item">
                                    <i class="fas fa-exclamation-triangle text-danger mt-1"></i>
                                    <div>
                                        <strong>{{ $report->disease_name }}</strong><br>
                                        <small class="text-muted">{{ $report->region }} - {{ $report->created_at->diffForHumans() }}</small>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="p-4 text-center text-muted">لا توجد بلاغات وبائية حالياً</div>
                        @endif
                        <a href="{{ route('veterinaire.epidemic.reports.index') }}" class="text-center p-2 d-block small text-success fw-bold" style="border-top: 1px solid #eee;">مشاهدة الكل</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="search-container shadow-sm">
        <h3 class="fw-bold" style="color: var(--primary-dark)"><i class="fas fa-search me-2"></i> ابحث عن الأدوية عند الموزعين</h3>
        <p class="text-muted">اكتب اسم الدواء لتظهر لك الاقتراحات وأماكن الموزعين</p>
        
        <form action="{{ route('veterinaire.market') }}" method="GET" class="search-bar-custom shadow-sm">
            <input type="text" name="medicine" id="medicineInput" list="medicinesList" 
                   placeholder="اكتب اسم الدواء هنا..." value="{{ request('medicine') }}" autocomplete="off">
            <datalist id="medicinesList"></datalist>
            <button type="submit">بحث الآن</button>
        </form>
    </div>

    @if(isset($results))
    <div class="row mb-5">
        <h4 class="fw-bold mb-4">نتائج البحث عن: {{ $searchQuery }}</h4>
        @forelse($results as $item)
        <div class="col-md-4 mb-4">
            <div class="card medicine-card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge bg-success-subtle text-success">متوفر</span>
                        <span class="fw-bold text-success">{{ $item->prix }} د.ج</span>
                    </div>
                    <h5 class="fw-bold text-primary">{{ $item->medicine_name }}</h5>
                    <p class="small text-muted mb-1"><i class="fas fa-truck"></i> الموزع: {{ $item->distributeur_name }}</p>
                    <p class="small text-muted"><i class="fas fa-map-marker-alt"></i> {{ $item->distributeur_address }}</p>

                    <button class="btn btn-success w-100 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#orderModal{{ $loop->index }}">
                        <i class="fas fa-cart-plus me-1"></i> طلب شراء
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">لم نجد أي منتج بهذا الاسم حالياً.</p>
        </div>
        @endforelse
    </div>
    @endif

    <div class="bg-white p-4 rounded-4 shadow-sm mt-4">
        <h4 class="fw-bold mb-3"><i class="fas fa-map-marked-alt text-success"></i> مواقع الموزعين في منطقتك</h4>
        <div id="map"></div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // التحكم في قائمة التنبيهات
    function toggleNotifs() {
        document.getElementById("notif-menu").classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.closest('.notifications-dropdown')) {
            var menu = document.getElementById("notif-menu");
            if (menu.classList.contains('show')) menu.classList.remove('show');
        }
    }

    const userLat = {{ Auth::user()->latitude ?? 36.4621 }};
    const userLng = {{ Auth::user()->longitude ?? 7.4311 }};
    const map = L.map('map').setView([userLat, userLng], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© AgroDz'
    }).addTo(map);

    L.marker([userLat, userLng]).addTo(map).bindPopup("<b>موقعك الحالي</b>").openPopup();

    @if(isset($allDistributors))
        const allDists = @json($allDistributors);
        allDists.forEach(dist => {
            if(dist.latitude && dist.longitude) {
                L.marker([dist.latitude, dist.longitude], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                        iconSize: [25, 41], iconAnchor: [12, 41]
                    })
                }).addTo(map).bindPopup(`<b>الموزع: ${dist.name}</b><br>${dist.address}`);
            }
        });
    @endif

    // الاقتراحات البحثية
    const medInput = document.getElementById('medicineInput');
    const medList = document.getElementById('medicinesList');

    if (medInput) {
        medInput.addEventListener('input', function() {
            const query = this.value;
            if (query.length < 2) { medList.innerHTML = ''; return; }

            fetch("{{ route('veterinaire.api.suggestions') }}?q=" + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    medList.innerHTML = ''; 
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = (typeof item === 'object') ? item.nom : item; 
                        medList.appendChild(option);
                    });
                });
        });
    }
</script>
</body>
</html>