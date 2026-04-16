<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>لوحة الموزع</title>

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

/* Sections */
.section {
    margin-top: 20px;
    background: white;
    padding: 15px;
    border-radius: 10px;
}

/* Buttons */
.btn {
    padding: 8px 12px;
    background: green;
    color: white;
    border: none;
    border-radius: 5px;
}

/* Table */
table {
    width: 100%;
    margin-top: 10px;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}

th {
    background: #27ae60;
    color: white;
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>الموزع</h2>

    <a href="#">🏠 الرئيسية</a>
    <a href="#">📦 المنتجات</a>
    <a href="#">🛒 الطلبات</a>
</div>

<!-- Content -->
<div class="content">

    <!-- Header -->
    <div class="header">
        <h2>لوحة التحكم</h2>
        <p>Gestion de Stock</p>
    </div>

    <!-- Cards -->
    <div class="cards">
        <div class="card">
            <h3>عدد المنتجات</h3>
            <p>12</p>
        </div>

        <div class="card">
            <h3>طلبات جديدة</h3>
            <p>5</p>
        </div>

        <div class="card">
            <h3>نفذ المخزون</h3>
            <p>2</p>
        </div>
    </div>

    <!-- Gestion de Stock -->
    <div class="section">
        <h3>📦 إدارة المخزون</h3>

        <table>
            <tr>
                <th>اسم المنتج</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>الإجراء</th>
            </tr>

            <tr>
                <td>دواء 1</td>
                <td>20</td>
                <td>500 DA</td>
                <td><button class="btn">تعديل</button></td>
            </tr>

            <tr>
                <td>دواء 2</td>
                <td>0</td>
                <td>300 DA</td>
                <td><button class="btn">إضافة</button></td>
            </tr>
        </table>
    </div>

    <!-- Commandes -->
    <div class="section">
        <h3>🛒 الطلبات</h3>

        <table>
            <tr>
                <th>اسم الفلاح</th>
                <th>المنتج</th>
                <th>الحالة</th>
                <th>الإجراء</th>
            </tr>

            <tr>
                <td>محمد</td>
                <td>دواء 1</td>
                <td>في الانتظار</td>
                <td><button class="btn">قبول</button></td>
            </tr>
        </table>
    </div>

</div>

</body>
</html>