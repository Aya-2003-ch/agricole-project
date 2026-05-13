<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - لوحة تحكم المربي</title>

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
        }

        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg); display: flex; }

        /* Sidebar */
        .sidebar { 
            width: 260px; 
            height: 100vh; 
            background: var(--secondary); 
            color: white; 
            position: fixed; 
            right: 0; 
            z-index: 1000; 
            display: flex; 
            flex-direction: column; 
        }
        .sidebar h2 { text-align: center; padding: 20px; border-bottom: 1px solid #2d6a4f; }
        .sidebar a { display: block; padding: 15px 25px; color: #d1d1d1; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: var(--primary); color: white; }

        /* تسجيل الخروج */
        .logout-section {
            margin-top: auto;
            border-top: 1px solid #2d6a4f;
        }
        .btn-logout {
            width: 100%;
            background: none;
            border: none;
            color: #ffbaba;
            padding: 15px 25px;
            text-align: right;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
            font-family: inherit;
        }
        .btn-logout:hover {
            background: var(--danger);
            color: white;
        }

        /* Main Content */
        .content { margin-right: 260px; width: calc(100% - 260px); padding: 30px; }

        .header { background: var(--white); padding: 20px; border-radius: 15px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }

        .dashboard-grid { display: grid; grid-template-columns: 1fr 400px; gap: 20px; }

        .section-card { background: var(--white); padding: 20px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        
        #map { height: 450px; width: 100%; border-radius: 12px; margin-bottom: 15px; }

        /* Vet Cards */
        .vets-list-container { max-height: 400px; overflow-y: auto; }
        .vet-card { 
            border: 1px solid #eee; padding: 15px; border-radius: 12px; margin-bottom: 15px; transition: 0.3s; 
        }
        .vet-card:hover { border-color: var(--primary); transform: translateY(-2px); }
        .vet-name { font-weight: bold; color: var(--secondary); margin-bottom: 5px; display: block; }
        .vet-address { font-size: 13px; color: #666; margin-bottom: 8px; }
        .vet-dist { font-size: 11px; background: #e8f5e9; color: var(--primary); padding: 3px 8px; border-radius: 10px; font-weight: bold; }

        .btn-book { 
            width: 100%; background: var(--primary); color: white; border: none; padding: 10px; 
            border-radius: 8px; cursor: pointer; margin-top: 10px; font-weight: bold;
        }

        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); align-items: center; justify-content: center; }
        .modal-content { background: white; padding: 25px; border-radius: 15px; width: 400px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group textarea, .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>AgroDz 🚜</h2>
        <a href="#" class="active"><i class="fas fa-home"></i> الرئيسية</a>
        <a href="{{ route('eleveur.isticharati') }}"><i class="fas fa-file-medical"></i> استشاراتي</a>
        <a href="{{ route('eleveur.chats') }}"><i class="fas fa-comments"></i> المحادثات</a>
        <!-- تم الإبقاء على رابط الأوبئة هنا في السايدبار -->
        <a href="{{ route('eleveur.epidemic.reports') }}" style="color: #ffbaba;"><i class="fas fa-biohazard"></i> بلاغات الأوبئة</a>
        
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </div>

    <div class="content">
        <div class="header">
            <h3>مرحباً بك، {{ Auth::user()->name }} 🌾</h3>
            <span id="coords-display" style="font-size: 12px; color: #888;"></span>
        </div>

        <div class="dashboard-grid">
            <div class="section-card">
                <h4><i class="fas fa-map-marked-alt"></i> تحديد موقع المزرعة</h4>
                <div id="map"></div>
                <form action="{{ route('eleveur.updateLocation') }}" method="POST">
                    @csrf
                    <input type="hidden" name="lat" id="lat-input" value="{{ Auth::user()->latitude }}">
                    <input type="hidden" name="lng" id="lng-input" value="{{ Auth::user()->longitude }}">
                    <button type="submit" class="btn-book" style="background: var(--secondary);">حفظ الموقع الحالي</button>
                </form>
            </div>

            <div class="side-sections">
                <div class="section-card">
                    <h4><i class="fas fa-user-md"></i> قائمة الأطباء الذكية</h4>
                    <div id="vets-list" class="vets-list-container">
                        <p style="text-align: center; color: #999;">جاري البحث عن أقرب الأطباء...</p>
                    </div>
                </div>
                <!-- تم حذف البطاقة الحمراء فقط من هنا -->
            </div>
        </div>
    </div>

    <div id="consultationModal" class="modal">
        <div class="modal-content">
            <h3 style="margin-top:0;">إرسال طلب استشارة</h3>
            <form action="{{ route('eleveur.consultations.store') }}" method="POST">
                @csrf
                <input type="hidden" name="veterinaire_id" id="modal_vet_id">
                <div class="form-group">
                    <label>الطبيب المختار:</label>
                    <input type="text" id="modal_vet_name" readonly>
                </div>
                <div class="form-group">
                    <label>سبب الاستشارة (Motif):</label>
                    <textarea name="motif" rows="4" required placeholder="اشرح حالة الحيوان..."></textarea>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-book">إرسال الطلب</button>
                    <button type="button" onclick="closeModal()" class="btn-book" style="background: #ccc; color: #333;">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var userLat = {{ Auth::user()->latitude ?? 36.46 }};
        var userLng = {{ Auth::user()->longitude ?? 7.43 }};

        var map = L.map('map').setView([userLat, userLng], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var farmMarker = L.marker([userLat, userLng], {draggable: true}).addTo(map);
        var vetsLayer = L.layerGroup().addTo(map);

        function loadVets(lat, lng) {
            document.getElementById('lat-input').value = lat;
            document.getElementById('lng-input').value = lng;
            document.getElementById('coords-display').innerText = `إحداثياتك: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;

            fetch(`{{ route('eleveur.nearby.vets') }}?lat=${lat}&lng=${lng}`)
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('vets-list');
                    list.innerHTML = '';
                    vetsLayer.clearLayers();

                    data.forEach(vet => {
                        L.marker([vet.latitude, vet.longitude], {
                            icon: L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                                iconSize: [20, 32], iconAnchor: [10, 32]
                            })
                        }).addTo(vetsLayer).bindPopup(`د. ${vet.name}`);

                        list.innerHTML += `
                            <div class="vet-card">
                                <span class="vet-name">د. ${vet.name}</span>
                                <div class="vet-address"><i class="fas fa-map-marker-alt"></i> ${vet.address || 'العنوان غير متوفر'}</div>
                                <span class="vet-dist">${parseFloat(vet.distance).toFixed(2)} كم عنك</span>
                                <button onclick="openModal('${vet.id}', '${vet.name}')" class="btn-book">طلب استشارة</button>
                            </div>
                        `;
                    });
                });
        }

        farmMarker.on('dragend', function() {
            var pos = farmMarker.getLatLng();
            loadVets(pos.lat, pos.lng);
        });

        function openModal(id, name) {
            document.getElementById('modal_vet_id').value = id;
            document.getElementById('modal_vet_name').value = "د. " + name;
            document.getElementById('consultationModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('consultationModal').style.display = 'none';
        }

        window.onload = () => loadVets(userLat, userLng);
    </script>
</body>
</html>