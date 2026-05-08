<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي - AgroDz</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #14532d;
            --accent-green: #16a34a;
            --bg-light: #f8fafc;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-dark), #064e3b);
            position: fixed;
            right: 0;
            color: white;
            padding: 30px 15px;
        }

        .sidebar a {
            display: flex; align-items: center; gap: 12px; padding: 14px;
            color: #ecfdf5; text-decoration: none; border-radius: 12px; transition: 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: var(--accent-green); color: white;
        }

        .main-content { margin-right: 260px; padding: 40px; }

        .order-card {
            border: none; border-radius: 15px; background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: 0.3s;
            margin-bottom: 20px; border-right: 5px solid #ddd;
        }

        /* تلوين الحافة حسب حالة الطلب */
        .status-pending { border-right-color: #f59e0b; }
        .status-completed { border-right-color: #10b981; }
        .status-rejected { border-right-color: #ef4444; }

        .status-badge {
            padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: bold;
        }

        .info-label { color: #64748b; font-size: 13px; margin-bottom: 2px; }
        .info-value { color: #1e293b; font-weight: 600; }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="text-center mb-4 pb-3 border-bottom border-white border-opacity-10">
        <h4>🌿 AgroDz</h4>
    </div>
    <a href="{{ route('veterinaire.dashboard') }}"><i class="fas fa-th-large"></i> الرئيسية</a>
    <a href="{{ route('veterinaire.my_orders') }}" class="active"><i class="fas fa-shopping-basket"></i> سجل الطلبات</a>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> خروج
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</nav>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">سجل طلبات الشراء</h2>
        <span class="badge bg-white text-dark shadow-sm p-2 px-3 rounded-pill">إجمالي الطلبات: {{ $orders->count() }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @forelse($orders as $order)
        <div class="col-12">
            <div class="card order-card {{ 'status-'.$order->status }}">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 border-start">
                            <div class="info-label">الدواء المطلوب</div>
                               <div class="info-value fs-5 text-success">
                              {{ $order->produit->nom ?? 'غير متوفر' }}
                             </div>
                            <div class="mt-2">
                                <span class="text-muted small">الكمية: </span>
                                <span class="badge bg-light text-dark border">{{ $order->quantity }} وحدة</span>
                            </div>
                        </div>

                        <div class="col-md-3 border-start">
                            <div class="info-label">الموزع (المستلم)</div>
                            <div class="info-value text-primary">{{ $order->receiver->name ?? 'موزع غير معروف' }}</div>
                            <div class="small text-muted mt-1"><i class="fas fa-calendar-alt me-1"></i> {{ $order->created_at->format('Y-m-d H:i') }}</div>
                        </div>

                        <div class="col-md-4 border-start">
                            <div class="mb-2">
                                <i class="fas fa-phone-alt text-muted me-1 small"></i>
                                <span class="info-value" style="font-size: 14px;">{{ $order->phone }}</span>
                            </div>
                            <div>
                                <i class="fas fa-map-marker-alt text-muted me-1 small"></i>
                                <span class="info-value" style="font-size: 14px;">{{ $order->address }}</span>
                            </div>
                        </div>

                        <div class="col-md-2 text-center">
                         @if($order->status == 'pending')
                          <span class="status-badge bg-warning-subtle text-warning border border-warning">قيد الانتظار</span>
    
                          @elseif($order->status == 'accepted') 
                         <span class="status-badge bg-success-subtle text-success border border-success">تم القبول</span>
    
                         @elseif($order->status == 'rejected')
                         <span class="status-badge bg-danger-subtle text-danger border border-danger">مرفوض</span>
        
                         @else
                           <span class="status-badge bg-secondary-subtle text-secondary border border-secondary">{{ $order->status }}</span>
                          @endif
                         </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="bg-white p-5 rounded-4 shadow-sm">
                <i class="fas fa-box-open fa-4x text-muted mb-3 opacity-25"></i>
                <h4 class="text-muted">لا يوجد لديك أي طلبات حالياً</h4>
                <p>ابحث عن الأدوية في لوحة التحكم وقم بإرسال أول طلب لك.</p>
                <a href="{{ route('veterinaire.dashboard') }}" class="btn btn-success rounded-pill px-4">ابدأ البحث</a>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>