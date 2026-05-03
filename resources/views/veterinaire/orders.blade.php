<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طلبات الأدوية</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: right; }
        th { background-color: #16a34a; color: white; }
    </style>
</head>
<body>
    <div class="card">
        <h2>📦 قائمة طلبات الأدوية</h2>
        <table>
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>تاريخ الطلب</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commandes as $commande)
                <tr>
                    <td>{{ $commande->id }}</td>
                    <td>{{ $commande->created_at }}</td>
                    <td>قيد المعالجة</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <a href="{{ route('veterinaire.dashboard') }}">العودة للرئيسية</a>
    </div>
</body>
</html>