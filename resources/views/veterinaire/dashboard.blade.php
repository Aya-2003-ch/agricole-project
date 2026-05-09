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
            display: flex; align-items: center; gap: 12px; padding: 14px;
            margin-bottom: 8px; color: #ecfdf5; text-decoration: none;
            border-radius: 12px; transition: 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: var(--accent-green); transform: translateX(-5px); color: white;
        }

        .main-content { margin-right: 260px; padding: 30px; }

        /* SEARCH SECTION - التعديل المطلوب: بسيط وعادي */
        .search-container {
            background: var(--white);
            color: var(--text-main); 
            border-radius: 20px; 
            padding: 30px;
            margin-bottom: 30px; 
            box-shadow: var(--shadow); 
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .search-bar-custom {
            background: #f1f5f9; 
            border-radius: 15px; 
            padding: 8px;
            display: flex; 
            max-width: 800px; 
            margin: 20px auto 0;
            border: 1px solid #cbd5e1;
        }

        .search-bar-custom input {
            border: none; flex: 1; padding: 10px 20px; outline: none;
            font-size: 16px; border-radius: 15px; color: #333;
            background: transparent;
        }

        .search-bar-custom button {
            background: var(--accent-green); color: white; border: none;
            padding: 10px 30px; border-radius: 12px; font-weight: bold;
            cursor: pointer; transition: 0.3s;
        }

        .badge-notify {
            background: #ef4444; color: white; font-size: 11px;
            padding: 3px 7px; border-radius: 50%; position: relative;
            top: -2px; font-weight: bold;
        }

        #map { height: 450px; border-radius: 20px; z-index: 1; border: 1px solid #e2e8f0; }

        .medicine-card {
            border: none; border-radius: 18px; transition: 0.3s;
            background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .medicine-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand">🌿 AgroDz البيطري</div>
    
    <a href="{{ route('veterinaire.dashboard') }}" class="active"><i class="fas fa-th-large"></i> الرئيسية</a>
    
    <a href="{{ route('veterinaire.consultations') }}">
        <i class="fas fa-stethoscope"></i> الاستشارات
        {{-- التعديل: إظهار العدد الحقيقي للاستشارات الجديدة --}}
        @php
            $count = \App\Models\Consultation::where('veterinaire_id', Auth::id())->where('status', 'pending')->count();
        @endphp
        @if($count > 0)
            <span class="badge-notify">{{ $count }}</span>
        @endif
    </a>

    <a href="{{ route('veterinaire.my_orders') }}">
        <i class="fas fa-shopping-basket"></i> سجل الطلبات
        @if(isset($orderNotifications) && $orderNotifications > 0)
            <span class="badge-notify">{{ $orderNotifications }}</span>
        @endif
    </a>

    <a href="{{ route('veterinaire.chats') }}"><i class="fas fa-comments"></i> الدردشة</a>
    <a href="{{ route('veterinaire.profile') }}"><i class="fas fa-user-md"></i> الملف الشخصي</a>
    
    <a href="{{ route('veterinaire.report') }}" class="text-danger mt-4 fw-bold">
        <i class="fas fa-biohazard"></i> التبليغ عن وباء
    </a>

    <a href="{{ route('logout') }}" style="margin-top: auto;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> خروج
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</nav>

<div class="main-content">
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="p-4 bg-white rounded-4 shadow-sm border-end border-5 border-success">
                <h2 class="fw-bold">مرحباً، دكتور {{ Auth::user()->name }} 👋</h2>
                <p class="text-muted">ابحث عن الأدوية، تواصل مع الموزعين، وتابع استشارات الفلاحين.</p>
            </div>
        </div>
    </div>

    <!-- التعديل: شريط بحث بسيط بدون خلفية خضراء -->
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
        {{-- المودال الخاص بالطلب يبقى كما هو --}}
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">عذراً، لم نجد أي منتج بهذا الاسم حالياً.</p>
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