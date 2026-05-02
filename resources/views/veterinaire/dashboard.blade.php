<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>لوحة البيطري</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- LEAFLET -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f4f7f6;
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    height: 100vh;
    background: linear-gradient(180deg, #14532d, #16a34a);
    position: fixed;
    color: white;
    padding: 20px;
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
    margin-left: 240px;
    padding: 30px;
}

/* HEADER */
.header {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}

/* WELCOME */
.welcome-box {
    font-size: 20px;
    font-weight: bold;
    color: #14532d;
    background: #dcfce7;
    padding: 10px;
    border-radius: 10px;
}

/* NOTIF */
.notif-box {
    background: #fff3cd;
    padding: 10px;
    border-radius: 10px;
    margin-top: 10px;
    font-weight: bold;
}

/* SEARCH */
.search-box {
    margin: 20px 0;
}

.search-box input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
}

#results {
    background: white;
    border-radius: 10px;
    margin-top: 5px;
}

.result-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

/* MAP */
#map {
    height: 300px;
    width: 100%;
    border-radius: 10px;
    margin-top: 20px;
}

/* SERVICES */
.services {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.service-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
}

.service-card i {
    font-size: 30px;
    color: #16a34a;
    margin-bottom: 10px;
}

.chat-btn {
    display: inline-block;
    margin-top: 10px;
    padding: 8px;
    background: #16a34a;
    color: white;
    border-radius: 8px;
    text-decoration: none;
}

</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>👨‍⚕️ البيطري</h2>

    <a href="#"><i class="fas fa-home"></i> الرئيسية</a>

    <a href="{{ route('veterinaire.consultations') }}">
        <i class="fas fa-notes-medical"></i> الاستشارات
    </a>

    <a href="{{ route('veterinaire.profile') }}">
        <i class="fas fa-user"></i> صفحتي
    </a>

    <a href="{{ route('logout') }}" class="logout"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        تسجيل الخروج
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
    </form>
</div>

<!-- CONTENT -->
<div class="content">

    <!-- HEADER -->
    <div class="header">
        <div class="welcome-box">
            <span id="welcome-text"></span>
        </div>

        <div class="notif-box">
            🔔 <span id="notif-count">0</span> طلب جديد
        </div>
    </div>

    <!-- SEARCH -->
    <div class="search-box">
        <input type="text" id="search" placeholder="🔍 ابحث عن دواء">
        <div id="results"></div>
    </div>

    <!-- MAP -->
    <div>
        <h3>📍 الموزعين القريبين</h3>
        <div id="map"></div>
    </div>

    <!-- SERVICES -->
    <div class="services">

        <div class="service-card">
            <i class="fas fa-notes-medical"></i>
            <h3>الطلبات</h3>
        </div>

        <div class="service-card">
            <i class="fas fa-pills"></i>
            <h3>الأدوية</h3>
        </div>

        <div class="service-card">
            <i class="fas fa-comments"></i>
            <h3>المحادثات</h3>
            <a href="#" class="chat-btn">فتح</a>
        </div>

    </div>

</div>

<!-- JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// 🔥 WELCOME
const name = "{{ Auth::user()->name }}";
const messages = [
    👋 مرحباً ${name},
    🩺 جاهز لمساعدة الفلاحين؟,
    🌾 إدارة الاستشارات بسهولة,
];

let i = 0;
setInterval(() => {
    document.getElementById("welcome-text").innerText = messages[i];
    i = (i + 1) % messages.length;
}, 3000);

// 🔔 NOTIFICATIONS
function loadNotifications(){
    fetch('/notifications')
        .then(res => res.json())
        .then(data => {
            document.getElementById('notif-count').innerText = data.count;
        });
}
setInterval(loadNotifications, 5000);
loadNotifications();

// 🔍 SEARCH
document.addEventListener("DOMContentLoaded", function () {
document.getElementById('search').addEventListener('keyup', function() {

    let query = this.value;

    if(query.length < 2){
        document.getElementById('results').innerHTML = "";
        return;
    }

    fetch('/live-search?search=' + query)
    .then(response => response.json())
    .then(data => {

        console.log("DATA:", data); // 👈 مهم

        let resultsDiv = document.getElementById('results');
        resultsDiv.innerHTML = "";

        if(data.length === 0){
            resultsDiv.innerHTML = "<p>❌ لا توجد نتائج</p>";
            return;
        }

        data.forEach(item => {

            let div = document.createElement('div');
            div.classList.add('result-item');

            div.innerHTML = `
                <strong>${item.nom}</strong><br>
                📍 ${item.address ?? 'غير متوفر'}<br>
                💰 ${item.prix} دج
            `;

            resultsDiv.appendChild(div);
        });

    })
    .catch(error => {
        console.error("ERROR:", error);
    });

});
});

// 🌍 MAP
const userLat = {{ Auth::user()->latitude ?? 36.75 }};
const userLng = {{ Auth::user()->longitude ?? 3.05 }};

const map = L.map('map').setView([userLat, userLng], 10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

L.marker([userLat, userLng]).addTo(map)
    .bindPopup("📍 موقعك");

fetch('/nearby-distributeurs')
    .then(res => res.json())
    .then(data => {

        data.forEach(d => {
            L.marker([d.latitude, d.longitude])
                .addTo(map)
                .bindPopup(`${d.name}<br>${d.address}`);
        });

    });

</script>

</body>
</html>