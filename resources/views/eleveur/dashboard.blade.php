<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - لوحة تحكم المربي</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #2d6a4f; 
            --secondary: #1b4332;
            --accent: #d4a373;
            --bg: #f4f7f5;
            --white: #ffffff;
            --danger: #ef4444;
            --soft-danger: rgba(239, 68, 68, 0.1);
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
            box-shadow: -4px 0 15px rgba(0,0,0,0.05);
        }
        .sidebar h2 { text-align: center; padding: 25px 20px; border-bottom: 1px solid rgba(45, 106, 79, 0.4); margin: 0; font-weight: 700; }
        
        /* روابط القائمة الجانبية مع تأثيرات الحركة المطلوبة */
        .sidebar a { 
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 25px; 
            color: #b3c6bd; 
            text-decoration: none; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            position: relative;
            font-weight: 500;
        }

        /* حركات الأيقونات الذكية عند الـ Hover */
        .sidebar a i {
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-size: 1.1rem;
        }

        .sidebar a:hover { 
            background: rgba(45, 106, 79, 0.2); 
            color: white; 
            padding-right: 30px; /* حركة سحب خفيفة لليمين */
        }

        /* حركة ارتدادية أو دوران خفيف للأيقونة عند تمرير الفأرة */
        .sidebar a:hover i {
            transform: scale(1.2) rotate(-8deg);
            color: #52b788;
        }

        .sidebar a.active { 
            background: var(--primary); 
            color: white; 
            font-weight: bold;
        }
        
        .sidebar a.active i {
            color: white !important;
        }

        /* قسم تسجيل الخروج الآمن */
        .logout-section {
            margin-top: auto;
            border-top: 1px solid rgba(45, 106, 79, 0.4);
            padding: 15px;
        }
        
        .btn-logout {
            width: 100%;
            background: none;
            border: none;
            color: #ffbaba;
            padding: 12px 20px;
            text-align: right;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-family: inherit;
            font-weight: 500;
        }

        .btn-logout i {
            transition: transform 0.3s ease;
        }

        .btn-logout:hover {
            background: var(--danger);
            color: white;
        }

        /* تأثير حركة أيقونة الخروج عند الـ Hover */
        .btn-logout:hover i {
            transform: translateX(-5px) scale(1.1); /* دفع الأيقونة لليسار باتجاه الباب */
        }

        /* Main Content */
        .content { margin-right: 260px; width: calc(100% - 260px); padding: 30px; box-sizing: border-box; }

        .header { background: var(--white); padding: 20px 25px; border-radius: 15px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }

        .dashboard-grid { display: grid; grid-template-columns: 1fr 400px; gap: 25px; }

        .section-card { background: var(--white); padding: 25px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 20px; }
        .section-card h4 { margin-top: 0; margin-bottom: 20px; color: var(--secondary); font-size: 18px; display: flex; align-items: center; gap: 10px; }
        
        #map { height: 450px; width: 100%; border-radius: 12px; margin-bottom: 15px; border: 1px solid #e2e8f0; }

        /* Vet Cards */
        .vets-list-container { max-height: 440px; overflow-y: auto; padding-left: 5px; }
        .vet-card { 
            border: 1px solid #e2e8f0; padding: 18px; border-radius: 12px; margin-bottom: 15px; transition: 0.3s; background: #fff;
        }
        .vet-card:hover { border-color: var(--primary); transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.04); }
        .vet-name { font-weight: bold; color: var(--secondary); margin-bottom: 6px; display: block; font-size: 16px; }
        .vet-address { font-size: 13px; color: #667085; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .vet-dist { font-size: 12px; background: #e8f5e9; color: var(--primary); padding: 4px 10px; border-radius: 20px; font-weight: bold; display: inline-block; }

        .btn-book { 
            width: 100%; background: var(--primary); color: white; border: none; padding: 12px; 
            border-radius: 10px; cursor: pointer; margin-top: 12px; font-weight: bold; transition: 0.3s; font-size: 14px;
        }
        .btn-book:hover { background: var(--secondary); transform: translateY(-1px); }

        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
        .modal-content { background: white; padding: 30px; border-radius: 20px; width: 480px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #344054; font-size: 14px; }
        .form-group textarea, .form-group input, .form-group select { width: 100%; padding: 12px; border: 1px solid #d0d5dd; border-radius: 10px; background: white; font-family: inherit; box-sizing: border-box; transition: 0.3s; }
        .form-group textarea:focus, .form-group input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1); }
        
        /* تصميم صندوق الحيوانات المطور */
        .animals-checkbox-list {
            border: 1px solid #d0d5dd;
            border-radius: 10px;
            max-height: 180px;
            overflow-y: auto;
            padding: 8px;
            background: #fff;
        }
        .animal-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: 0.2s;
            border-radius: 6px;
        }
        .animal-item:hover {
            background-color: #f0fdf4;
        }
        .animal-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }
        .animal-item label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
            width: 100%;
            color: #475467;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>AgroDz 🚜</h2>
        <a href="#" class="active"><i class="fas fa-home"></i> <span>الرئيسية</span></a>
        <a href="{{ route('eleveur.animals.index') }}"><i class="fas fa-paw"></i> <span>إدارة قطيعي</span></a>
        <a href="{{ route('eleveur.isticharati') }}"><i class="fas fa-file-medical"></i> <span>استشاراتي</span></a>
        <a href="{{ route('eleveur.chats') }}"><i class="fas fa-comments"></i> <span>المحادثات</span></a>
        
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> 
                    <span>تسجيل الخروج</span>
                </button>
            </form>
        </div>
    </div>

    <div class="content">
        <div class="header">
            <div style="display: flex; align-items: center; gap: 25px;">
                <h3 style="margin:0; color: var(--secondary); font-weight: 700;">مرحباً بك، {{ Auth::user()->name }} 🌾</h3>
            </div>
            <span id="coords-display" style="font-size: 13px; color: #667085; background: #eea; padding: 4px 12px; border-radius: 20px; font-weight: 500;"></span>
        </div>

        <div class="dashboard-grid">
            <div class="section-card">
                <h4><i class="fas fa-map-marked-alt text-success"></i> تحديد موقع المزرعة وتحديثها</h4>
                <div id="map"></div>
                <form action="{{ route('eleveur.updateLocation') }}" method="POST">
                    @csrf
                    <input type="hidden" name="lat" id="lat-input" value="{{ Auth::user()->latitude }}">
                    <input type="hidden" name="lng" id="lng-input" value="{{ Auth::user()->longitude }}">
                    <button type="submit" class="btn-book" style="background: var(--secondary); padding: 14px;">حفظ الموقع الجغرافي للمزرعة</button>
                </form>
            </div>

            <div class="side-sections">
                <div class="section-card">
                    <h4><i class="fas fa-user-md text-success"></i> العيادات البيطرية الأقرب إليك</h4>
                    <div id="vets-list" class="vets-list-container">
                        <p style="text-align: center; color: #999;">جاري تحديد موقعك وجلب البياطرة...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="consultationModal" class="modal">
        <div class="modal-content">
            <h3 style="margin-top:0; color: var(--secondary); font-weight: 700; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;"><i class="fas fa-paper-plane text-success"></i> إنشاء طلب استشارة طبية</h3>
            <form action="{{ route('eleveur.consultations.store') }}" method="POST">
                @csrf
                <input type="hidden" name="veterinaire_id" id="modal_vet_id">
                
                <div class="form-group">
                    <label>الطبيب المعالج المختار:</label>
                    <input type="text" id="modal_vet_name" readonly style="background-color: #f8f9fa; font-weight: bold; color: var(--primary); border-color: #eaecf0;">
                </div>

                <div class="form-group">
                    <label>اختر الحيوان المعني أو الحالات المراد فحصها:</label>
                    <input type="text" id="searchAnimalInput" onkeyup="filterAnimals()" placeholder="🔍 ابحث بالنوع أو الكود التعريفي للرأس..." style="margin-bottom: 10px; border-color: var(--primary);">
                    
                    <div class="animals-checkbox-list" id="animalsContainer">
                        @foreach($animals as $animal)
                            <div class="animal-item">
                                <input type="checkbox" name="animal_ids[]" value="{{ $animal->id }}" id="animal_{{ $animal->id }}">
                                <label for="animal_{{ $animal->id }}">
                                    <strong>{{ $animal->type }}</strong> 
                                    {{ $animal->identification_code ? '[كود: ' . $animal->identification_code . ']' : '' }}
                                    {{ $animal->age ? '(السن: ' . $animal->age . ')' : '' }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>الأعراض الملاحظة بالتفصيل (Motif):</label>
                    <textarea name="motif" rows="4" required placeholder="يرجى كتابة تقرير مختصر عن الحالة المرضية أو الأعراض الملاحظة على الماشية لمساعدة الطبيب..."></textarea>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 5px;">
                    <button type="submit" class="btn-book" style="margin-top:0; flex: 2; padding: 12px;">إرسال الاستشارة الآن</button>
                    <button type="button" onclick="closeModal()" class="btn-book" style="background: #f2f4f7; color: #344054; margin-top:0; flex: 1; padding: 12px;">إلغاء</button>
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
            document.getElementById('coords-display').innerText = `الموقع الجغرافي الحالي: ${lat.toFixed(4)} , ${lng.toFixed(4)}`;

            fetch(`{{ route('eleveur.nearby.vets') }}?lat=${lat}&lng=${lng}`)
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('vets-list');
                    list.innerHTML = '';
                    vetsLayer.clearLayers();

                    if(data.length === 0) {
                        list.innerHTML = '<p style="text-align: center; color: #667085; padding-top: 20px;">لا يوجد بياطرة مسجلين قريبين من موقعك حالياً.</p>';
                        return;
                    }

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
                                <div class="vet-address"><i class="fas fa-map-marker-alt text-danger"></i> ${vet.address || 'العنوان غير محدد بالخريطة'}</div>
                                <span class="vet-dist"><i class="fas fa-route"></i> يبتعد عنك ${parseFloat(vet.distance).toFixed(2)} كم</span>
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