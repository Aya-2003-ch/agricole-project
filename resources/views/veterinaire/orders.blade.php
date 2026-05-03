<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - طلبات الأدوية</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            --primary-dark: #14532d;
            --accent-green: #16a34a;
            --bg-light: #f1f5f9;
            --white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            margin: 0;
            padding: 20px;
            color: #334155;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
        }

        .card {
            background: var(--white);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-top: 8px solid var(--accent-green);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 15px;
        }

        .card-header h2 {
            margin: 0;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            padding: 15px;
            text-align: right;
            border-bottom: 2px solid #edf2f7;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 15px;
        }

        tr:hover {
            background-color: #fcfdfd;
        }

        /* تنسيق حالات الطلب */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .status-pending { background: #fef3c7; color: #92400e; } /* قيد المعالجة */
        .status-shipped { background: #dcfce7; color: #166534; } /* تم الشحن */
        .status-canceled { background: #fee2e2; color: #991b1b; } /* ملغى */

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-back:hover {
            color: var(--primary-dark);
        }

        .order-id {
            font-family: monospace;
            font-weight: bold;
            color: var(--accent-green);
        }

        @media (max-width: 600px) {
            table, thead, tbody, th, td, tr { display: block; }
            th { display: none; }
            tr { margin-bottom: 15px; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px; }
            td { text-align: left; padding: 8px; border: none; display: flex; justify-content: space-between; }
            td::before { content: attr(data-label); font-weight: bold; color: #64748b; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-boxes-stacked"></i> قائمة طلبات الأدوية</h2>
                <a href="{{ route('veterinaire.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-right"></i> العودة للرئيسية
                </a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>تاريخ الطلب</th>
                        <th>الموزع</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                    <tr>
                        <td data-label="رقم الطلب" class="order-id">#{{ $commande->id }}</td>
                        <td data-label="تاريخ الطلب">{{ $commande->created_at->format('Y-m-d H:i') }}</td>
                        <td data-label="الموزع">{{ $commande->distributeur->name ?? 'موزع عام' }}</td>
                        <td data-label="الحالة">
                            @if($commande->status == 'pending' || !$commande->status)
                                <span class="status-badge status-pending"><i class="far fa-clock"></i> قيد المعالجة</span>
                            @elseif($commande->status == 'delivered')
                                <span class="status-badge status-shipped"><i class="fas fa-check"></i> تم الاستلام</span>
                            @else
                                <span class="status-badge status-canceled">ملغى</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="fas fa-box-open" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                            لا توجد طلبات سابقة حالياً.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>