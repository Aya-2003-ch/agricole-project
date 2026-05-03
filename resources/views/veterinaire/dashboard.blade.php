<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة البيطري - إدارة المشروع</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Cairo', sans-serif;
        }

        body {
            background: #f4f7f6;
            direction: rtl; /* لضمان التنسيق العربي */
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            height: 100vh;
            background: linear-gradient(180deg, #14532d, #16a34a);
            position: fixed;
            color: white;
            padding: 20px;
            right: 0; /* التثبيت على اليمين */
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            margin-bottom: 10px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
        }

        .logout {
            position: absolute;
            bottom: 20px;
            width: 80%;
        }

        /* CONTENT */
        .content {
            margin-right: 240px; /* الهامش من اليمين بسبب السايدبار */
            padding: 30px;
        }

        /* HEADER */
        .header {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .welcome-box {
            font-size: 20px;
            font-weight: bold;
            color: #14532d;
            background: #dcfce7;
            padding: 10px 20px;
            border-radius: 10px;
            min-width: 300px;
        }

        .notif-box {
            background: #fff3cd;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: bold;
            color: #856404;
        }

        /* SEARCH */
        .search-box {
            margin: 20px 0;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        #results {
            background: white;
            border-radius: 10px;
            margin-top: 5px;
            position: absolute;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .result-item {
            padding: 12px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .result-item:hover { background: #f9f9f9; }

        /* MAP */
        #map {
            height: 350px;
            width: 100%;
            border-radius: 15px;
            margin-top: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* SERVICES */
        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .service-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .service-card i {
            font-size: 40px;
            color: #16a34a;
            margin-bottom: 15px;
        }

        .chat-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 25px;
            background: #16a34a;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h2>👨‍⚕️ لوحة البيطري</h2>

    <a href="{{ route('veterinaire.dashboard') }}"><i class="fas fa-home"></i> الرئيسية</a>

    <a href="{{ route('veterinaire.consultations') }}">
        <i class="fas fa-notes-medical"></i> الاستشارات
    </a>

    <a href="{{ route('veterinaire.profile') }}">
        <i class="fas fa-user"></i> ملفي الشخصي
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <a href="#" class="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
    </a>
</div>

<div class="content">

    <div class="header">
        <div class="welcome-box">
            <span id="welcome-text">جارِ التحميل...</span>
        </div>

        <div class="notif-box">
            🔔 <span id="notif-count">0</span> استشارات جديدة
        </div>
    </div>

    <div class="search-box">
        <input type="text" id="search" placeholder="🔍 ابحث عن أدوية بيطرية أو فلاحية...">
        <div id="results"></div>
    </div>

    <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <h3>📍 الموزعون والصيدليات القريبة</h3>
        <div id="map"></div>
    </div>

    <div class="services">

        <a href="{{ route('veterinaire.orders') }}" style="text-decoration: none; color: inherit;">
            <div class="service-card">
                <i class="fas fa-file-invoice-dollar"></i>
                <h3>إدارة الطلبات</h3>
                <p style="color: #666; font-size: 14px; margin-top: 5px;">متابعة طلبات الأدوية</p>
            </div>
        </a>

        <a href="{{ route('veterinaire.medicines') }}" style="text-decoration: none; color: inherit;">
            <div class="service-card">
                <i class="fas fa-pills"></i>
                <h3>مخزن الأدوية</h3>
                <p style="color: #666; font-size: 14px; margin-top: 5px;">عرض وإضافة الأدوية</p>
            </div>
        </a>

        <div class="service-card">
            <i class="fas fa-comments"></i>
            <h3>المحادثات المباشرة</h3>
            <p style="color: #666; font-size: 14px; margin-top: 5px;">تواصل مع الفلاحين</p>
            <a href="{{ route('veterinaire.chats') }}" class="chat-btn">فتح الشات</a>
        </div>

    </div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // 1. نظام الترحيب الديناميكي
    const userName = "{{ Auth::user()->name }}";
    const messages = [
        `👋 مرحباً دكتور ${userName}`,
        `🩺 هل هناك استشارات جديدة اليوم؟`,
        `🌾 لنساعد فلاحينا في حماية محاصيلهم`,
    ];

    let msgIdx = 0;
    const welcomeEl = document.getElementById("welcome-text");
    
    function rotateMessages() {
        welcomeEl.innerText = messages[msgIdx];
        msgIdx = (msgIdx + 1) % messages.length;
    }
    rotateMessages();
    setInterval(rotateMessages, 4000);

    // 2. جلب التنبيهات (Real-time Simulation)
    function checkNotifications(){
        fetch('/notifications') // تأكدي من وجود هذا المسار في web.php
            .then(res => res.json())
            .then(data => {
                document.getElementById('notif-count').innerText = data.count || 0;
            }).catch(e => console.log("Notification endpoint not ready"));
    }
    setInterval(checkNotifications, 10000);

    // 3. البحث المباشر عن الأدوية
    document.getElementById('search').addEventListener('input', function() {
        let query = this.value;
        let resultsDiv = document.getElementById('results');

        if(query.length < 2) {
            resultsDiv.innerHTML = "";
            return;
        }

        fetch('/live-search?search=' + query)
        .then(response => response.json())
        .then(data => {
            resultsDiv.innerHTML = "";
            data.forEach(item => {
                let div = document.createElement('div');
                div.classList.add('result-item');
                div.innerHTML = `<strong>${item.nom}</strong> - 💰 ${item.prix} دج`;
                resultsDiv.appendChild(div);
            });
        });
    });

    // 4. إعداد الخريطة
    const lat = {{ Auth::user()->latitude ?? 36.46 }}; // الافتراضي قالمة
    const lng = {{ Auth::user()->longitude ?? 7.43 }};

    const map = L.map('map').setView([lat, lng], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // ماركر موقع البيطري
    L.marker([lat, lng]).addTo(map).bindPopup("<b>أنت هنا</b>").openPopup();

    // جلب الموزعين (اختياري إذا كان الـ API جاهز)
    fetch('/nearby-distributeurs')
        .then(res => res.json())
        .then(data => {
            data.forEach(d => {
                L.marker([d.latitude, d.longitude]).addTo(map)
                    .bindPopup(`<b>${d.name}</b><br>${d.address}`);
            });
        }).catch(e => console.log("Map markers endpoint not ready"));

</script>

</body>
</html>