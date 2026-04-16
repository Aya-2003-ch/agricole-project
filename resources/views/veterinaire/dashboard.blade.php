<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة البيطري</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background-color: #f4f6f9;
        }

        /* الشريط الجانبي */
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #2c3e50;
            position: fixed;
            color: white;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        /* المحتوى */
        .content {
            margin-left: 220px;
            padding: 20px;
        }

        .header {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* الكروت */
        .cards {
            display: flex;
            gap: 20px;
        }

        .card {
            flex: 1;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .card h3 {
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: green;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: darkgreen;
        }

        /* جدول */
        table {
            width: 100%;
            background-color: white;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #27ae60;
            color: white;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>البيطري</h2>

    <a href="#">🏠 الرئيسية</a>
    <a href="/veterinaire/consultations">📋 الطلبات</a>
    <a href="#">⭐ التقييمات</a>
</div>

<!-- Content -->
<div class="content">

    <!-- Header -->
    <div class="header">
        <h2>مرحبا بك 👨‍⚕️</h2>
        <p>هنا يمكنك إدارة الاستشارات والطلبات</p>
    </div>

    <!-- Cards -->
    <div class="cards">
        <div class="card">
            <h3>طلبات جديدة</h3>
            <p>5</p>
        </div>

        <div class="card">
            <h3>طلبات قيد الانتظار</h3>
            <p>3</p>
        </div>

        <div class="card">
            <h3>تمت المعالجة</h3>
            <p>10</p>
        </div>
    </div>

    <!-- Table -->
    <table>
        <tr>
            <th>اسم الفلاح</th>
            <th>المشكلة</th>
            <th>الحالة</th>
            <th>الإجراء</th>
        </tr>

        <tr>
            <td>محمد</td>
            <td>مرض في البقر</td>
            <td>في الانتظار</td>
            <td>
                <a class="btn" href="#">قبول</a>
            </td>
        </tr>

        <tr>
            <td>أحمد</td>
            <td>مشكل في الأغنام</td>
            <td>جديد</td>
            <td>
                <a class="btn" href="#">قبول</a>
            </td>
        </tr>
    </table>

</div>

</body>
</html>