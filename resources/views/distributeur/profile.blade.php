<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>AgroDz - الملف الشخصي</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    
    <style>
        :root {
            --primary-dark: #2d4a36;
            --accent-green: #5a8d5a;
            --light-green: #f0fdf4;
            --bg-body: #f8fafc;
            --white: #ffffff;
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            --radius: 16px;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg-body);
            color: var(--text-dark);
        }

        /* HEADER (Style AgroDz) */
        .header {
            background: linear-gradient(135deg, var(--primary-dark), var(--accent-green));
            color: white;
            text-align: center;
            padding: 40px 20px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: var(--shadow);
        }

        .container {
            max-width: 1000px;
            margin: -30px auto 30px; /* تداخل بسيط مع الهيدر */
            padding: 0 20px;
        }

        /* CARDS */
        .card {
            background: var(--white);
            padding: 25px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .title {
            color: var(--primary-dark);
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid var(--light-green);
            padding-bottom: 10px;
        }

        /* INFO GRID */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
        }

        .info-box {
            padding: 15px;
            background: var(--light-green);
            border-radius: 12px;
            border-right: 4px solid var(--accent-green);
        }

        .info-box strong {
            color: var(--accent-green);
            font-size: 0.9rem;
            display: block;
            margin-bottom: 5px;
        }

        .info-box span {
            color: var(--primary-dark);
            font-weight: 600;
        }

        /* FORMS */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(90, 141, 90, 0.1);
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: var(--primary-dark);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn:hover {
            background: var(--accent-green);
            transform: translateY(-2px);
        }

        /* MAP */
        #map {
            height: 350px;
            border-radius: 12px;
            z-index: 1;
            border: 2px solid var(--light-green);
        }

        /* BACK BUTTON */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            padding: 12px 25px;
            background: #e2e8f0;
            color: #475569;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .back-link:hover {
            background: #cbd5e1;
            color: #1e293b;
        }

        @media (max-width: 768px) {
            .bottom-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="header">
    <h2>👤 الملف الشخصي</h2>
    <p>مرحباً بك، {{ $user->name }} - إدارة بياناتك الشخصية</p>
</div>

<div class="container">

    /* USER INFO */
    <div class="card">
        <div class="title"><i class="fas fa-info-circle"></i> معلوماتي الأساسية</div>
        <div class="info-grid">
            <div class="info-box">
                <strong>الاسم الكامل</strong>
                <span>{{ $user->name }}</span>
            </div>
            <div class="info-box">
                <strong>البريد الإلكتروني</strong>
                <span>{{ $user->email }}</span>
            </div>
            <div class="info-box">
                <strong>رقم الهاتف</strong>
                <!-- تم التعديل هنا ليعمل التحقق بشكل صحيح -->
                <span>{{ !empty($user->telephone) ? $user->telephone : 'غير متوفر' }}</span>
            </div>
            <div class="info-box">
                <strong>العنوان الحالي</strong>
                <span>{{ !empty($user->address) ? $user->address : 'غير متوفر' }}</span>
            </div>
            <div class="info-box">
                <strong>الإحداثيات (Lat/Lng)</strong>
                <span>{{ $user->latitude ?? '0' }} / {{ $user->longitude ?? '0' }}</span>
            </div>
        </div>
    </div>

    <!-- MAP -->
    <div class="card">
        <div class="title"><i class="fas fa-map-marker-alt"></i> موقعي الجغرافي</div>
        <div id="map"></div>
    </div>

    <!-- EDIT  -->
    <div class="bottom-grid">
        <div class="card">
            <div class="title"><i class="fas fa-user-edit"></i> تعديل البيانات</div>
            <form method="POST" action="{{ route('distributeur.profile.update') }}">
                @csrf
                <label style="font-size: 12px; color: var(--text-gray);">الاسم</label>
                <input type="text" name="name" value="{{ $user->name }}">
                
                <label style="font-size: 12px; color: var(--text-gray);">الهاتف</label>
                <input type="text" name="telephone" value="{{ $user->telephone }}">
                
                <label style="font-size: 12px; color: var(--text-gray);">العنوان</label>
                <input type="text" name="address" value="{{ $user->address }}">
                
                <label style="font-size: 12px; color: var(--text-gray);">الإيميل</label>
                <input type="email" name="email" value="{{ $user->email }}">

                <button class="btn" type="submit">تحديث الملف</button>
            </form>
        </div>

        <div class="card">
            <div class="title"><i class="fas fa-key"></i> الأمان</div>
            <form method="POST" action="#">
                @csrf
                <input type="password" name="old_password" placeholder="كلمة السر الحالية">
                <input type="password" name="new_password" placeholder="كلمة السر الجديدة">
                <button class="btn" type="submit" style="background: var(--accent-green);">تغيير كلمة السر</button>
            </form>
        </div>
    </div>

    <a href="{{ route('distributeur.dashboard') }}" class="back-link">
        <i class="fas fa-arrow-right"></i> العودة للوحة التحكم
    </a>

</div>

<!-- Scripts -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // جلب الإحداثيات من السيرفر أو وضع إحداثيات افتراضية للجزائر
    const lat = {{ $user->latitude ?? 36.75 }};
    const lng = {{ $user->longitude ?? 3.05 }};

    const map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; AgroDz Contributors'
    }).addTo(map);

    const marker = L.marker([lat, lng]).addTo(map)
        .bindPopup("<b>📍 موقعك المسجل</b>")
        .openPopup();
});
</script>

</body>
</html>