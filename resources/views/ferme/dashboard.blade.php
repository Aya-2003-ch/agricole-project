<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>لوحة الفلاح</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #f4f6f9;
}

/* Sidebar */
.sidebar {
    width: 220px;
    height: 100vh;
    background: #2c3e50;
    position: fixed;
    color: white;
    padding-top: 20px;
}

.sidebar h2 {
    text-align: center;
}

.sidebar a {
    display: block;
    padding: 15px;
    color: white;
    text-decoration: none;
}

.sidebar a:hover {
    background: #34495e;
}

/* Content */
.content {
    margin-left: 220px;
    padding: 20px;
}

/* Header */
.header {
    background: white;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
}

/* Search */
.search-box {
    margin: 20px 0;
    display: flex;
}

.search-box input {
    flex: 1;
    padding: 10px;
    border-radius: 5px 0 0 5px;
    border: 1px solid #ccc;
}

.search-box button {
    padding: 10px;
    border: none;
    background: green;
    color: white;
    border-radius: 0 5px 5px 0;
}

/* Cards */
.cards {
    display: flex;
    gap: 20px;
}

.card {
    flex: 1;
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Map */
.map {
    margin-top: 20px;
    height: 250px;
    background: #ddd;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Notifications */
.notifications {
    margin-top: 20px;
    background: white;
    padding: 15px;
    border-radius: 10px;
}

.notif {
    padding: 10px;
    border-bottom: 1px solid #eee;
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>الفلاح</h2>

    <a href="#">🏠 الرئيسية</a>
    <a href="#">📦 الطلبات</a>
    <a href="#">👨‍⚕️ الأطباء</a>
    <a href="#">🏪 الموزعين</a>
</div>

<!-- Content -->
<div class="content">

    <!-- Header -->
    <div class="header">
        <h2>مرحبا بك 👨‍🌾</h2>
        <p>ابحث عن طبيب أو موزع قريب منك</p>
    </div>

    <!-- Search -->
    <div class="search-box">
        <input type="text" placeholder="ابحث عن طبيب أو موزع...">
        <button>بحث</button>
    </div>

    <!-- Cards -->
    <div class="cards">
        <div class="card">
            <h3>طلباتي</h3>
            <p>5</p>
        </div>

        <div class="card">
            <h3>أطباء قريبين</h3>
            <p>3</p>
        </div>

        <div class="card">
            <h3>موزعين</h3>
            <p>4</p>
        </div>
    </div>

    <!-- Map -->
    <div class="map">
        🗺️ هنا ستكون الخريطة
    </div>

    <!-- Notifications -->
    <div class="notifications">
        <h3>🔔 إشعارات</h3>

        <div class="notif">⚠️ انتشار مرض في المنطقة</div>
        <div class="notif">📦 نقص في دواء معين</div>
    </div>

</div>

</body>
</html>