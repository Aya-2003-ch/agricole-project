<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>صفحتي الشخصية</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f4f7f6;
}

/* HEADER */
.header {
    background: linear-gradient(180deg, #14532d, #16a34a);
    color: white;
    text-align: center;
    padding: 25px;
}

/* CONTAINER */
.container {
    max-width: 1000px;
    margin: 30px auto;
    padding: 15px;
}

/* TOP */
.top {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

.title {
    color: #14532d;
    font-weight: bold;
    margin-bottom: 15px;
    font-size: 18px;
}

/* GRID */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.info {
    padding: 15px;
    background: #f9fafb;
    border-radius: 10px;
    border: 1px solid #eee;
}

.info strong {
    color: #14532d;
}

/* BOTTOM */
.bottom {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* CARD */
.card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* INPUT */
input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
}

/* BUTTON */
.btn {
    width: 100%;
    padding: 10px;
    background: #16a34a;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 10px;
}

.btn:hover {
    background: #14532d;
}

/* MAP */
#map {
    height: 300px;
    border-radius: 10px;
}

/* BACK */
.back {
    display: block;
    text-align: center;
    margin-top: 20px;
    padding: 12px;
    background: #2c3e50;
    color: white;
    text-decoration: none;
    border-radius: 10px;
}

.back:hover {
    background: #1f2d3a;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }

    .bottom {
        grid-template-columns: 1fr;
    }
}
#map {
    height: 300px;
    border-radius: 10px;
}
</style>

</head>

<body>

<!-- HEADER -->
<div class="header">
    <h2>👤 صفحتي الشخصية</h2>
    <p>إدارة المعلومات والموقع</p>
</div>

<div class="container">

    <!-- USER INFO -->
    <div class="top">
        <div class="title">📌 معلوماتي</div>

        <div class="info-grid">

            <div class="info">
                <strong>الاسم:</strong><br>
                {{ $user->name }}
            </div>

            <div class="info">
                <strong>الإيميل:</strong><br>
                {{ $user->email }}
            </div>

            <div class="info">
                <strong>رقم الهاتف:</strong><br>
                {{ !empty($user->phone) ? $user->telephone : 'غير متوفر' }}
            </div>

            <div class="info">
                <strong>العنوان:</strong><br>
                {{ !empty($user->address) ? $user->address : 'غير متوفر' }}
            </div>

            <div class="info">
                <strong>Latitude:</strong><br>
                {{ !empty($user->latitude) ? $user->latitude : 'غير متوفر' }}
            </div>

            <div class="info">
                <strong>Longitude:</strong><br>
                {{ !empty($user->longitude) ? $user->longitude : 'غير متوفر' }}
            </div>

        </div>
    </div>

    <!-- MAP -->
    <div class="card">
        <div class="title">📍 موقعي على الخريطة</div>
        <div id="map"></div>
    </div>

    <!-- EDIT -->
    <div class="bottom">

        <div class="card">
            <div class="title">✏️ تعديل المعلومات</div>

            <form method="POST" action="#">
                @csrf

                <input type="text" name="name" value="{{ $user->name }}">
                <input type="text" name="telephone" value="{{ $user->telephone }}">
                <input type="text" name="address" value="{{ $user->address }}">
                <input type="email" name="email" value="{{ $user->email}}">

                <button class="btn" type="submit">حفظ</button>
            </form>
        </div>

        <div class="card">
            <div class="title">🔐 تغيير كلمة السر</div>

            <form method="POST" action="#">
                @csrf

                <input type="password" name="old_password" placeholder="القديمة">
                <input type="password" name="new_password" placeholder="الجديدة">

                <button class="btn" type="submit">تغيير</button>
            </form>
        </div>

    </div>

    <a href="{{ route('distributeur.dashboard') }}" class="back">
        ⬅ العودة
    </a>

</div>

<!-- GOOGLE MAP -->
<div id="map"></div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const lat = {{ $user->latitude ?? 36.75 }};
    const lng = {{ $user->longitude ?? 3.05 }};

    const map = L.map('map').setView([lat, lng], 10);

    // OpenStreetMap layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Marker
    L.marker([lat, lng]).addTo(map)
        .bindPopup("📍 موقعي الحالي")
        .openPopup();
});
</script>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</body>
</html>