<x-guest-layout>

<style>
body {
    background: linear-gradient(135deg, #eafaf1, #f4f7f5);
}

form {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

label {
    color: #1e7d4f !important;
    font-weight: bold;
}

input, select {
    border-radius: 10px !important;
    border: 1px solid #ccc !important;
    padding: 10px !important;
}

button {
    background: linear-gradient(90deg, #27ae60, #1e7d4f) !important;
    border-radius: 30px !important;
    padding: 10px 20px !important;
    transition: 0.3s;
    color: white;
    border: none;
}

button:hover {
    transform: scale(1.05);
}

/* MAP */
#map {
    height: 250px;
    border-radius: 10px;
    margin-top: 10px;
}
</style>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div>
        <x-input-label for="name" value="الاسم" />
        <x-text-input id="name" class="block mt-1 w-full"
            type="text" name="name"
            :value="old('name')" required autofocus />
    </div>

    <div class="mt-4">
        <x-input-label for="email" value="البريد الإلكتروني" />
        <x-text-input id="email" class="block mt-1 w-full"
            type="email" name="email"
            :value="old('email')" required />
    </div>

    <div class="mt-4">
        <x-input-label for="telephone" value="رقم الهاتف" />
        <x-text-input id="telephone" class="block mt-1 w-full"
            type="text" name="telephone"
            :value="old('telephone')" required />
    </div>

    <div class="mt-4">
        <x-input-label for="address" value="العنوان" />
        <x-text-input id="address" class="block mt-1 w-full"
            type="text" name="address"
            :value="old('address')" required />
    </div>

    <div class="mt-4">
        <x-input-label value="📍 الموقع" />

        <button type="button" onclick="getLocation()">
            تحديد موقعي تلقائياً
        </button>

        <div id="map"></div>

        <p id="location-status" style="font-size:13px;color:gray;margin-top:5px;"></p>
    </div>

    <input type="hidden" name="latitude" id="lat">
    <input type="hidden" name="longitude" id="lng">

    <div class="mt-4">
        <x-input-label for="role" value="نوع الحساب" />

        <select name="role" id="role" class="block mt-1 w-full" required>
            <option value="">-- اختر نوع الحساب --</option>
            <option value="eleveur">🐄 فلاح (Éleveur)</option>
            <option value="veterinaire">🩺 بيطري (Vétérinaire)</option>
            <option value="distributeur">🚚 موزع (Distributeur)</option>
            <option value="admin">🛡️ مدير النظام (Admin)</option> </select>
    </div>

    <div class="mt-4">
        <x-input-label for="password" value="كلمة المرور" />
        <x-text-input id="password" class="block mt-1 w-full"
            type="password" name="password" required />
    </div>

    <div class="mt-4">
        <x-input-label for="password_confirmation" value="تأكيد كلمة المرور" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full"
            type="password" name="password_confirmation" required />
    </div>

    <div class="flex items-center justify-between mt-6">

        <a href="{{ route('login') }}" style="color:#1e7d4f;">
            عندك حساب؟
        </a>

        <x-primary-button>
            تسجيل الحساب
        </x-primary-button>

    </div>
</form>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
let map, marker;

document.addEventListener("DOMContentLoaded", function () {

    map = L.map('map').setView([36.75, 3.05], 8);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    map.on('click', function (e) {

        if (marker) {
            map.removeLayer(marker);
        }

        marker = L.marker(e.latlng).addTo(map);

        document.getElementById('lat').value = e.latlng.lat;
        document.getElementById('lng').value = e.latlng.lng;
    });
});

// GPS auto detect
function getLocation() {

    let status = document.getElementById("location-status");

    if (navigator.geolocation) {

        status.innerHTML = "⏳ جاري تحديد الموقع...";

        navigator.geolocation.getCurrentPosition(function(position) {

            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            document.getElementById("lat").value = lat;
            document.getElementById("lng").value = lng;

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lng]).addTo(map);
            map.setView([lat, lng], 13);

            status.innerHTML = "✅ تم تحديد الموقع بنجاح";

        }, function() {
            status.innerHTML = "❌ تم رفض نظام تحديد المواقع (GPS)";
        });

    } else {
        status.innerHTML = "❌ المتصفح لا يدعم الـ GPS";
    }
}
</script>

</x-guest-layout>