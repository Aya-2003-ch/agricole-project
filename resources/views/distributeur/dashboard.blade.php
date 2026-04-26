<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>لوحة الموزع</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .welcome {
    font-size: 24px;
    font-weight: bold;
    color: #14532d;
    margin-bottom: 10px;
    background: #dcfce7;
    padding: 10px 15px;
    border-radius: 10px;
    display: inline-block;
}
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f4f7f6;
}

/* SIDEBAR (نفس البيطري) */
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

/* CARDS */
.cards {
    display: flex;
    gap: 20px;
}

.card {
    flex: 1;
    background: white;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

.card i {
    font-size: 30px;
    color: #16a34a;
    margin-bottom: 10px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <h2>📦 الموزع</h2>

    <a href="#"><i class="fas fa-home"></i> الرئيسية</a>

    <!-- إدارة المنتجات -->
    <a href="{{ route('produits.index') }}">
        <i class="fas fa-box"></i> إدارة المنتجات
    </a>

    <!-- صفحتي -->
    <a href="{{ route('distributeur.profile') }}">
        <i class="fas fa-user"></i> صفحتي
    </a>

    <!-- تسجيل الخروج -->
    <a href="{{ route('logout') }}"
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="fas fa-right-from-bracket"></i> تسجيل الخروج
  </a>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
  </form>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

</div>

<!-- CONTENT -->
<div class="content">

    <!-- HEADER -->
    <div class="header">
        <h2 class="welcome">
    👋 مرحبا {{ Auth::user()->name }}
      </h2>
        <p>Gestion de Stock</p>
    </div>

    <!-- CARDS (ديناميكيين لاحقاً من Laravel) -->
    <div class="cards">

        <div class="card">
            <i class="fas fa-box"></i>
            <h3>عدد المنتجات</h3>
            <p>{{ $totalProduits ?? '...' }}</p>
        </div>

        <div class="card">
            <i class="fas fa-shopping-cart"></i>
            <h3>الطلبات الجديدة</h3>
            <p>{{ $totalCommandes ?? '...' }}</p>
        </div>

    </div>

</div>

</body>
</html>