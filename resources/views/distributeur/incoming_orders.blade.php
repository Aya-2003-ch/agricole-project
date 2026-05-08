<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الطلبات الواردة | AgroDz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #344e41; 
            --primary-green: #588157;
            --light-bg: #f4f7f6;
        }
        body { 
            background-color: var(--light-bg); 
            font-family: 'Tajawal', sans-serif; 
            margin: 0;
            padding: 0;
        }
        
        .container-fluid { padding: 40px; max-width: 1300px; }
        
        .order-card {
            background: white; border-radius: 20px; padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-top: 5px solid var(--primary-green);
        }
        
        .table thead { background-color: #f8f9fa; }
        .table thead th { border-bottom: none; color: var(--primary-dark); padding: 15px; }

        .status-badge { padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 500; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-accepted { background: #d1e7dd; color: #0f5132; }
        .status-rejected { background: #f8d7da; color: #842029; }

        .role-badge {
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .back-btn {
            width: 40px; height: 40px;
            border-radius: 12px; background-color: #fff;
            border: 1px solid #eee; display: flex;
            align-items: center; justify-content: center;
            color: var(--primary-green); text-decoration: none;
            transition: 0.3s;
        }
        .back-btn:hover { background-color: var(--primary-green); color: #fff; transform: scale(1.05); }
        
        .action-btn { transition: 0.2s; border-radius: 10px; padding: 6px 16px; font-weight: 500; }
        .btn-accept:hover { background-color: #198754; color: white; transform: translateY(-2px); }
        .btn-reject:hover { background-color: #dc3545; color: white; transform: translateY(-2px); }
    </style>
</head>
<body>

    <div class="container-fluid mx-auto">
        <div class="order-card shadow border-0">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('distributeur.dashboard') }}" class="back-btn" title="العودة للوحة التحكم">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <div>
                        <h3 class="text-success fw-bold mb-0">قائمة الطلبات الواردة</h3>
                        <p class="text-muted small mb-0">إدارة طلبات الموزعين والأطباء البياطرة</p>
                    </div>
                </div>
                <div class="text-start">
                    <span class="badge bg-dark px-3 py-2" style="border-radius: 10px;">
                        إجمالي الطلبات: {{ $orders->count() }}
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية</th>
                            <th>المرسل</th>
                            <th>نوع الجهة</th> <th>رقم الهاتف</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold text-dark">{{ $order->produit?->nom ?? 'منتج غير متوفر' }}</td>
                            <td><span class="badge bg-light text-dark border px-3">{{ $order->quantity }}</span></td>
                            <td class="fw-medium text-secondary">{{ $order->sender->name }}</td>
                            
                            <td>
                                @if($order->sender->role == 'veterinaire')
                                    <span class="role-badge bg-info-subtle text-info border border-info">
                                        <i class="fas fa-user-md"></i> طبيب بيطري
                                    </span>
                                @else
                                    <span class="role-badge bg-primary-subtle text-primary border border-primary">
                                        <i class="fas fa-truck-moving"></i> موزع
                                    </span>
                                @endif
                            </td>

                            <td>{{ $order->phone ?? 'لا يوجد' }}</td>
                            <td class="text-muted small">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="status-badge status-pending">قيد الانتظار</span>
                                @elseif($order->status == 'accepted')
                                    <span class="status-badge status-accepted">تم القبول</span>
                                @else
                                    <span class="status-badge status-rejected">مرفوض</span>
                                @endif
                            </td>
                            <td>
                                @if($order->status == 'pending')
                                <div class="d-flex gap-2 justify-content-center">
                                    <form action="{{ route('distributeur.order.accept', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success action-btn btn-accept">
                                            <i class="fas fa-check"></i> قبول
                                        </button>
                                    </form>

                                    <form action="{{ route('distributeur.order.reject', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-danger action-btn btn-reject">
                                            <i class="fas fa-times"></i> رفض
                                        </button>
                                    </form>
                                </div>
                                @else
                                    <span class="text-muted small">لا توجد إجراءات</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="opacity-50">
                                    <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                                    <p class="fw-bold">لا توجد طلبات جديدة واردة في الوقت الحالي.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>