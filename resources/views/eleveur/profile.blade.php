<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>صفحتي</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

/* TOP SECTION (INFO) */
.top {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

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

/* BOTTOM SECTION */
.bottom {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* CARDS */
.card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* TITLE */
.title {
    color: #14532d;
    font-weight: bold;
    margin-bottom: 15px;
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

/* BACK BUTTON */
.back {
    display: block;
    text-align: center;
    margin-top: 20px;
    padding: 12px;
    background: #2c3e50;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    transition: 0.3s;
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
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h2>👤 صفحتي الشخصية</h2>
    <p>إدارة معلومات الحساب</p>
</div>

<div class="container">

    <! USER INFO>
    <div class="top">
        <div class="title">معلوماتي</div>

        <div class="info-grid">

            <div class="info">
                <strong>الاسم الكامل:</strong><br>
                {{ $user->name }}
            </div>

            <div class="info">
                <strong>الإيميل:</strong><br>
                {{ $user->email }}
            </div>

            <div class="info">
                <strong>رقم الهاتف:</strong><br>
                {{ $user->phone ?? 'غير متوفر' }}
            </div>

            <div class="info">
                <strong>العنوان:</strong><br>
                {{ $user->address ?? 'غير متوفر' }}
            </div>

        </div>
    </div>

    <!-- BOTTOM: EDIT + PASSWORD -->
    <div class="bottom">

        <!-- EDIT INFO -->
        <div class="card">
            <div class="title">✏️ تعديل المعلومات</div>

            <form method="POST" action="#">
                @csrf

                <input type="text" name="name" value="{{ $user->name }}" placeholder="الاسم الكامل">

                <input type="text" name="phone" value="{{ $user->phone }}" placeholder="رقم الهاتف">

                <input type="text" name="address" value="{{ $user->address }}" placeholder="العنوان">

                <button class="btn" type="submit">حفظ التعديلات</button>
            </form>
        </div>

        <!-- PASSWORD -->
        <div class="card">
            <div class="title">🔐 تغيير كلمة السر</div>

            <form method="POST" action="#">
                @csrf

                <input type="password" name="old_password" placeholder="كلمة السر القديمة">

                <input type="password" name="new_password" placeholder="كلمة السر الجديدة">

                <button class="btn" type="submit">تغيير كلمة السر</button>
            </form>
            </div>

    </div>

    <!-- BACK BUTTON -->
    <a href="{{ route('ferme.dashboard') }}" class="back">
        ⬅ العودة للصفحة الرئيسية
    </a>

</div>

</body>
</html>