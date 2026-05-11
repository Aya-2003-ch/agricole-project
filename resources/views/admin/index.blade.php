<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير - AgroDz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { background: #2d3436; min-height: 100vh; color: white; padding-top: 20px; position: fixed; right: 0; width: inherit; z-index: 1000; }
        .main-content { margin-right: 16.666667%; } 
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .table thead { background-color: #00b894; color: white; }
        .btn-delete { color: #d63031; cursor: pointer; transition: 0.3s; text-decoration: none; }
        .btn-delete:hover { color: #ff7675; }
        .nav-link-custom { color: white; text-decoration: none; padding: 15px; display: block; transition: 0.3s; border-radius: 5px; margin: 5px 10px; cursor: pointer; }
        .nav-link-custom:hover { background: #00b894; color: white; }
        .nav-link-custom.active { background: #00b894; }
        .stat-icon { font-size: 2.5rem; opacity: 0.3; position: absolute; left: 15px; top: 15px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-0 text-center">
            <h3 class="fw-bold text-success my-4">AgroDz</h3>
            <hr>
            <div class="nav flex-column">
                <a onclick="showSection('users-section')" class="nav-link-custom active" id="btn-users">
                    <i class="fas fa-users"></i> إدارة المستخدمين
                </a>
                <a onclick="showSection('stats-section')" class="nav-link-custom" id="btn-stats">
                    <i class="fas fa-chart-line"></i> الإحصائيات والأرباح
                </a>
            </div>
            <hr>
            <a href="/" class="btn btn-outline-light btn-sm mx-3">العودة للموقع</a>
        </div>

        <div class="col-md-10 main-content p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold" id="page-title">لوحة التحكم (الإدارة)</h2>
                <div class="text-muted">مرحباً بك، <span class="badge bg-dark">{{ auth()->user()->name }}</span></div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div id="users-section">
                <div class="card p-4 border-0">
                    <h4 class="mb-4 text-secondary"><i class="fas fa-list"></i> قائمة جميع المستخدمين</h4>
                    <table class="table table-hover align-middle text-center">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الدور (Role)</th>
                                <th>تاريخ الانضمام</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : ($user->role == 'eleveur' ? 'bg-success' : 'bg-primary') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($user->id !== auth()->id()) 
                                    <a href="{{ url('admin/delete/'.$user->id) }}" 
                                       class="btn-delete" 
                                       onclick="return confirm('هل أنت متأكد من حذف هذا الحساب نهائياً؟')">
                                        <i class="fas fa-trash-alt"></i> حذف
                                    </a>
                                    @else
                                    <span class="text-muted small">حسابك الحالي</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="stats-section" style="display: none;">
                <div class="row g-4 text-dark">
                    <div class="col-md-4">
                        <div class="card bg-white p-4 position-relative border-start border-success border-5">
                            <i class="fas fa-money-bill-wave stat-icon text-success"></i>
                            <h6 class="text-muted small">إجمالي المداخيل (Revenue)</h6>
                            <h2 class="fw-bold text-success">{{ number_format($totalRevenue, 2) }} د.ج</h2>
                            <small class="text-muted">مستمدة من الطلبيات المكتملة</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-white p-4 position-relative border-start border-primary border-5">
                            <i class="fas fa-shopping-cart stat-icon text-primary"></i>
                            <h6 class="text-muted small">عدد الطلبات الكلي</h6>
                            <h2 class="fw-bold text-primary">{{ $ordersCount }}</h2>
                            <small class="text-muted text-decoration-underline">طلبيات الأدوية والمعدات</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-white p-4 position-relative border-start border-warning border-5">
                            <i class="fas fa-stethoscope stat-icon text-warning"></i>
                            <h6 class="text-muted small">الاستشارات النشطة</h6>
                            <h2 class="fw-bold text-warning">{{ $activeConsultations }}</h2> 
                            <small class="text-muted text-decoration-underline">تفاعل البياطرة حالياً</small>
                        </div>
                    </div>
                </div>

              
              <div class="card mt-5 p-4 border-0 shadow-sm">
                    <h4 class="text-secondary mb-3">تحليل الأداء العام</h4>
                    <p class="text-muted small mb-4 text-center">النمو التراكمي لمنصة AgroDz بناءً على البيانات الحالية</p>
                    <div class="progress mb-4" style="height: 25px; border-radius: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, $users->count() * 10) }}%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">المستخدمين: {{ $users->count() }}</div>
                    </div>
                    <div class="progress" style="height: 25px; border-radius: 20px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ min(100, $ordersCount * 5) }}%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">الطلبات المنجزة</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function showSection(sectionId) {
        document.getElementById('users-section').style.display = 'none';
        document.getElementById('stats-section').style.display = 'none';
        document.getElementById(sectionId).style.display = 'block';
        const title = document.getElementById('page-title');
        title.innerText = (sectionId === 'users-section') ? 'إدارة المستخدمين' : 'الإحصائيات والأرباح';
        document.getElementById('btn-users').classList.remove('active');
        document.getElementById('btn-stats').classList.remove('active');
        if(sectionId === 'users-section') {
            document.getElementById('btn-users').classList.add('active');
        } else {
            document.getElementById('btn-stats').classList.add('active');
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>