<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>AgroDz</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

/* BACKGROUND */
body {
    background: #f4f7f5;
    color: #2c3e50;
}

/* NAVBAR */
nav {
    background: linear-gradient(90deg, #1e7d4f, #27ae60);
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

nav h2 {
    font-size: 22px;
}

nav a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: bold;
    transition: 0.3s;
}

nav a:hover {
    color: #d1f7dc;
}

/* HERO */
.hero {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    color: white;
    text-align: center;
    padding: 100px 20px;
    border-radius: 0 0 40px 40px;
}

.hero h1 {
    font-size: 45px;
    margin-bottom: 15px;
}

.hero p {
    font-size: 18px;
    opacity: 0.9;
    margin-bottom: 25px;
}

.btn {
    padding: 12px 25px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-weight: bold;
    margin: 10px;
    transition: 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-white {
    background: white;
    color: #27ae60;
}

.btn-white:hover {
    background: #eafaf1;
    transform: scale(1.05);
}

.btn-green {
    background: #145a32;
    color: white;
}

.btn-green:hover {
    background: #0f3d22;
    transform: scale(1.05);
}

/* SECTION */
.section {
    padding: 60px 20px;
    text-align: center;
}

.section h2 {
    margin-bottom: 30px;
    font-size: 28px;
    color: #1e7d4f;
}

/* CARDS */
.cards {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
}

.card {
    background: white;
    width: 260px;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-8px);
}

.card h3 {
    margin-bottom: 10px;
    color: #27ae60;
}

/* CTA */
.cta {
    background: linear-gradient(90deg, #eafaf1, #ffffff);
    padding: 60px 20px;
    text-align: center;
    margin-top: 40px;
}

.cta h2 {
    margin-bottom: 10px;
    color: #1e7d4f;
}

/* FOOTER */
footer {
    background: #1e2a2f;
    color: white;
    text-align: center;
    padding: 20px;
    margin-top: 40px;
    font-size: 14px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .hero h1 {
        font-size: 30px;
    }

    .cards {
        flex-direction: column;
        align-items: center;
    }
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav>
    <h2>🌿 AgroDz</h2>
    <div>
        <a href="#">Accueil</a>
        <a href="#">Produits</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('login') }}">Connexion</a>
        <a href="{{ route('register') }}">Inscription</a>
    </div>
</nav>

<!-- HERO -->
<div class="hero">
    <h1>AgroDz 🌱 منصة الفلاحة الذكية في الجزائر</h1>
    <p>بيع منتجاتك،توسيع زبائنك ومبيعاتك, الوصول لاقرب بيطري وموزع لك, مراقبة ومتابعة الامراض الحيوانية عن بعد   </p>

    <a href="{{ route('login') }}" class="btn btn-white">Se connecter</a>
    <a href="{{ route('register') }}" class="btn btn-green">Créer un compte</a>
</div>

<!-- SERVICES -->
<div class="section">
    <h2> خدماتنا</h2>

    <div class="cards">
        <div class="card">
            <h3>🌾 بيع المنتجات</h3>
            <p>بيع مباشر وسريع بين الموزع والمستهلك</p>
        </div>

        <div class="card">
            <h3>📦 إدارة ذكية</h3>
            <p>تحكم كامل في المخزون والمنتجات</p>
        </div>

        <div class="card">
            <h3>🚚 التوصيل</h3>
            <p>تنظيم الطلبات والتوصيل بسهولة</p>
        </div>

        <div class="card">
            <h3>👨‍🌾 دعم الفلاحين</h3>
            <p>   مرافقة رقمية لتسهيل حياة الفلاحين </p>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="cta">
    <h2>ابدأ رحلتك مع AgroDz 🚀</h2>
    <p>انضم الآن واستفد من خدماتنا </p>

    <br><br>
    <a href="{{ route('register') }}" class="btn btn-green">إنشاء حساب</a>
</div>

<!-- FOOTER -->
<footer>
    © 2026 AgroDz - منصة فلاحية جزائرية 🌿
</footer>

</body>
</html>