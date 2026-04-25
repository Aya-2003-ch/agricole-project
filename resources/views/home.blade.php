<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>AgroDz</title>

<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f1f5f3;
    color: #1e293b;
}

/* NAVBAR GLASS */
nav {
    position: sticky;
    top: 0;
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.7);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 40px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    z-index: 100;
}

nav h2 {
    color: #16a34a;
}

.nav-icons a {
    margin-left: 20px;
    font-size: 18px;
    color: #374151;
    transition: 0.3s;
}

.nav-icons a:hover {
    color: #16a34a;
    transform: scale(1.2);
}

/* HERO */
.hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 90px 50px;
    background: linear-gradient(135deg, #16a34a, #4ade80);
    color: white;
    border-radius: 0 0 50px 50px;
}

.hero-text {
    max-width: 50%;
}

.hero h1 {
    font-size: 45px;
    margin-bottom: 15px;
}

.hero p {
    margin-bottom: 25px;
    font-size: 18px;
}

.btn {
    padding: 12px 25px;
    border-radius: 30px;
    border: none;
    margin-right: 10px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
    text-decoration: none;
}

.btn-white {
    background: white;
    color: #16a34a;
}

.btn-dark {
    background: #064e3b;
    color: white;
}

.btn:hover {
    transform: scale(1.05);
}

/* SERVICES */
.section {
    padding: 80px 50px;
    text-align: center;
}

.section h2 {
    font-size: 32px;
    margin-bottom: 40px;
    color: #16a34a;
}

/* CARDS */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 18px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    transition: 0.3s;
    position: relative;
    overflow: hidden;
}

.card::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 5px;
    top: 0;
    left: 0;
    background: linear-gradient(90deg, #16a34a, #4ade80);
}

.card:hover {
    transform: translateY(-10px);
}

.card i {
    font-size: 28px;
    margin-bottom: 10px;
    color: #16a34a;
}

.card h3 {
    margin-bottom: 10px;
}

.card p {
    font-size: 14px;
    color: #555;
}

/* CTA */
.cta {
    text-align: center;
    padding: 70px;
    background: linear-gradient(135deg, #dcfce7, #ffffff);
}

.cta h2 {
    margin-bottom: 10px;
}

/* FOOTER */
footer {
    background: #0f172a;
    color: white;
    text-align: center;
    padding: 20px;
}

/* RESPONSIVE */
@media(max-width: 768px) {
    .hero {
        flex-direction: column;
        text-align: center;
    }

    .hero-text {
        max-width: 100%;
    }
}
</style>
</head>

<body>

<!-- NAVBAR ICONS -->
<nav>
    <h2>🌿 AgroDz</h2>

    <div class="nav-icons">
        <a href="#"><i class="fas fa-home"></i></a>
        <a href="#"><i class="fas fa-box"></i></a>
        <a href="#"><i class="fas fa-envelope"></i></a>
        <a href="{{ route('login') }}"><i class="fas fa-user"></i></a>
        <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i></a>
    </div>
</nav>

<!-- HERO -->
<div class="hero">
    <div class="hero-text">
        <h1>AgroDz 🐄🌿</h1>
        <p>منصة ذكية تجمع الفلاحين، الموزعين، والأطباء البيطريين</p>

        <a href="{{ route('login') }}" class="btn btn-white">Se connecter</a>
        <a href="{{ route('register') }}" class="btn btn-dark">Créer un compte</a>
    </div>

    <div>
        <i class="fas fa-leaf" style="font-size:120px;"></i>
    </div>
</div>

<!-- SERVICES -->
<div class="section">
    <h2>خدماتنا</h2>

    <div class="cards">

        <div class="card">
            <i class="fas fa-shopping-basket"></i>
            <h3>بيع المنتجات</h3>
            <p>بيع المنتجات الفلاحية الحيوانية و النباتية</p>
        </div>
        <div class="card">
            <i class="fas fa-warehouse"></i>
            <h3>إدارة المخزون</h3>
            <p>تحكم كامل في الكميات والأسعار</p>
        </div>

        <div class="card">
            <i class="fas fa-truck"></i>
            <h3>التوصيل</h3>
            <p>تنظيم الطلبات مع الموزعين</p>
        </div>

        <div class="card">
            <i class="fas fa-stethoscope"></i>
            <h3>بيطري</h3>
            <p>استشارات طبية للحيوانات</p>
        </div>

        <div class="card">
            <i class="fas fa-chart-line"></i>
            <h3>إحصائيات</h3>
            <p>تحليل الأداء والمبيعات</p>
        </div>

        <div class="card">
            <i class="fas fa-users"></i>
            <h3>شبكة</h3>
            <p>ربط جميع الفاعلين في الفلاحة</p>
        </div>

    </div>
</div>

<!-- CTA -->
<div class="cta">
    <h2>🚀 ابدأ الآن</h2>
    <p>أنشئ حسابك وابدأ رحلتك</p>
    <br>
    <a href="{{ route('register') }}" class="btn btn-dark">إنشاء حساب</a>
</div>

<!-- FOOTER -->
<footer>
    © 2026 AgroDz
</footer>

</body>
</html>