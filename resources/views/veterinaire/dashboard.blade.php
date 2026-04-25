<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>لوحة البيطري</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
    transition: 0.3s;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.2);
}

/* logout */
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
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

/* SEARCH */
.search-box {
    margin: 20px 0;
}

.search-box input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 10px;
    outline: none;
}

/* SERVICES */
.services {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.service-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    text-align: center;
    transition: 0.3s;
}

.service-card:hover {
    transform: translateY(-8px);
}

.service-card i {
    font-size: 35px;
    color: #16a34a;
    margin-bottom: 15px;
}

/* BUTTON */
.btn {
    padding: 8px 15px;
    border-radius: 20px;
    background: #16a34a;
    color: white;
    text-decoration: none;
    border: none;
    cursor: pointer;
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
    <a href="{{ route('profile.edit') }}">
    <i class="fas fa-user"></i> صفحتي
</a>

    <!-- LOGOUT -->
    <a href="{{ route('logout') }}" class="logout"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-right-from-bracket"></i> تسجيل الخروج
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
</div>

<!-- CONTENT -->
<div class="content">

    <!-- HEADER -->
    <div class="header">
        <h2>مرحبا 👨‍⚕️</h2>
        <p>إدارة الاستشارات والطلبات بسهولة</p>
    </div>

    <!-- SEARCH BOX -->
    <div class="search-box">
        <input type="text" id="search" placeholder="🔍 ابحث عن دواء...">
    </div>

    <!-- SERVICES -->
    <div class="services">

        <div class="service-card">
            <i class="fas fa-notes-medical"></i>
            <h3>إدارة الطلبات</h3>
            <p>عرض ومعالجة طلبات الفلاحين</p>
        </div>

        <div class="service-card">
            <i class="fas fa-bug"></i>
            <h3>تقارير الأمراض</h3>
            <p>متابعة الحالات المرضية للحيوانات</p>
        </div>

        <div class="service-card">
            <i class="fas fa-pills"></i>
            <h3>الأدوية</h3>
            <p>التحقق من توفر الأدوية</p>
        </div>

    </div>

</div>

<!-- SIMPLE SEARCH SCRIPT -->
<script>
document.getElementById("search").addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    let cards = document.querySelectorAll(".service-card");

    cards.forEach(card => {
        let text = card.innerText.toLowerCase();
        card.style.display = text.includes(value) ? "block" : "none";
    });
});
</script>

</body>
</html>