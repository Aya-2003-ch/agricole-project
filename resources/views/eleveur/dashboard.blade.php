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
        .sidebar h2 { text-align: center; padding: 20px; border-bottom: 1px solid #2d6a4f; margin: 0; }
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
            border-radius: 8px; cursor: pointer; margin-top: 10px; font-weight: bold; transition: 0.3s;
        }
        .btn-book:hover { background: var(--secondary); }

        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); align-items: center; justify-content: center; }
        .modal-content { background: white; padding: 25px; border-radius: 15px; width: 460px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: bold; color: #333; }
        .form-group textarea, .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; background: white; font-family: inherit; box-sizing: border-box; }
        
        /* تصميم صندوق الحيوانات المطور بالـ Checkboxes */
        .animals-checkbox-list {
            border: 1px solid #ddd;
            border-radius: 8px;
            max-height: 150px;
            overflow-y: auto;
            padding: 10px;
            background: #fff;
        }
        .animal-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: 0.2s;
        }
        .animal-item:hover {
            background-color: #f0fdf4;
        }
        .animal-item input[type="checkbox"] {
            width: auto;
            cursor: pointer;
        }
        .animal-item label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>AgroDz 🚜</h2>
        <a href="#" class="active"><i class="fas fa-home"></i> الرئيسية</a>
        <a href="{{ route('eleveur.animals.index') }}"><i class="fas fa-paw"></i> إدارة قطيعي</a>
        <a href="{{ route('eleveur.isticharati') }}"><i class="fas fa-file-medical"></i> استشاراتي</a>
        <a href="{{ route('eleveur.chats') }}"><i class="fas fa-comments"></i> المحادثات</a>
        
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
            <div style="display: flex; align-items: center; gap: 25px;">
                <h3 style="margin:0;">مرحباً بك، {{ Auth::user()->name }} 🌾</h3>
            </div>
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
            </div>
        </div>
    </div>

    <div id="consultationModal" class="modal">
        <div class="modal-content">
            <h3 style="margin-top:0; color: var(--secondary);"><i class="fas fa-paper-plane"></i> إرسال طلب استشارة</h3>
            <form action="{{ route('eleveur.consultations.store') }}" method="POST">
                @csrf
                <input type="hidden" name="veterinaire_id" id="modal_vet_id">
                
                <div class="form-group">
                    <label>الطبيب المختار:</label>
                    <input type="text" id="modal_vet_name" readonly style="background-color: #f1f5f9; font-weight: bold;">
                </div>

                <div class="form-group">
                    <label>اختر الحيوان أو مجموعة الحيوانات المعنية بالفحص:</label>
                    
                    <input type="text" id="searchAnimalInput" onkeyup="filterAnimals()" placeholder="🔍 اكتب هنا للبحث السريع (نوع، كود، سن)..." style="margin-bottom: 8px; border-color: var(--primary);">
                    
                    <div class="animals-checkbox-list" id="animalsContainer">
                        @foreach($animals as $animal)
                            <div class="animal-item">
                                <input type="checkbox" name="animal_ids[]" value="{{ $animal->id }}" id="animal_{{{ $animal->id }}}">
                                <label for="animal_{{{ $animal->id }}}">
                                    <strong>{{ $animal->type }}</strong> 
                                    {{ $animal->identification_code ? '[كود: ' . $animal->identification_code . ']' : '' }}
                                    {{ $animal->age ? '(السن: ' . $animal->age . ')' : '' }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>سبب الاستشارة (Motif):</label>
                    <textarea name="motif" rows="4" required placeholder="اشرح حالة الحيوانات والأعراض الملاحظة..."></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-book" style="margin-top:0;">إرسال الطلب</button>
                    <button type="button" onclick="closeModal()" class="btn-book" style="background: #e2e8f0; color: #334155; margin-top:0;">إلغاء</button>
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

        // دالة الـ JavaScript المطورة لتصفية عناصر الـ Checkbox فوراً أثناء الكتابة في حقل البحث
        function filterAnimals() {
            var input = document.getElementById("searchAnimalInput");
            var filter = input.value.toLowerCase();
            var container = document.getElementById("animalsContainer");
            var items = container.getElementsByClassName("animal-item");

            for (var i = 0; i < items.length; i++) {
                var label = items[i].getElementsByTagName("label")[0];
                var txtValue = label.textContent || label.innerText;
                
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    items[i].style.display = "flex";
                } else {
                    items[i].style.display = "none";
                }
            }
        }

        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                closeModal();
            }
        }

        window.onload = () => loadVets(userLat, userLng);
    </script>
</body>
</html>