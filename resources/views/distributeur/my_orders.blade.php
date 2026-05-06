<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي المرسلة | AgroDz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-green: #588157;
            --light-bg: #f4f7f6;
        }
        body { background-color: var(--light-bg); font-family: 'Tajawal', sans-serif; }
        .container { padding-top: 50px; padding-bottom: 50px; }
        .order-card {
            background: white; border-radius: 15px; padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-right: 5px solid var(--primary-green);
        }
        .status-badge { padding: 5px 15px; border-radius: 30px; font-size: 13px; font-weight: bold; }
        .back-btn {
            width: 35px; height: 35px; border-radius: 50%;
            background: #fff; border: 1px solid #ddd; display: inline-flex;
            align-items: center; justify-content: center; color: var(--primary-green);
            text-decoration: none; transition: 0.3s;
        }
        .back-btn:hover { background: var(--primary-green); color: #white; }
    </style>
</head>
<body>

<div class="container">
    <div class="order-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('distributeur.dashboard') }}" class="back-btn"><i class="fas fa-arrow-right"></i></a>
                <h3 class="mb-0 text-dark fw-bold">تتبع طلباتي المرسلة</h3>
            </div>
            <i class="fas fa-shopping-bag fa-2x text-success opacity-25"></i>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>المنتج</th>
                        <th>المزود (الموزع)</th>
                        <th>الكمية</th>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="fw-bold">{{ $order->produit?->nom }}</td>
                        <td>{{ $order->receiver?->name }}</td>
                        <td><span class="badge bg-secondary">{{ $order->quantity }}</span></td>
                        <td class="small text-muted">{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>
                            @if($order->status == 'pending')
                                <span class="status-badge bg-warning-subtle text-warning border border-warning">قيد الانتظار</span>
                            @elseif($order->status == 'accepted')
                                <span class="status-badge bg-success-subtle text-success border border-success">تم القبول</span>
                            @else
                                <span class="status-badge bg-danger-subtle text-danger border border-danger">مرفوض</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">لم تقم بإرسال أي طلبات بعد.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $orders->links() }} {{-- للتنقل بين الصفحات --}}
        </div>
    </div>
</div>

</body>
</html>