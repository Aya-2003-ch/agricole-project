<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير - AgroDz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #2d6a4f;
            --secondary-green: #1b4332;
            --light-green: #52b788;
            --soft-bg: #f4f7f5;
        }

        body { 
            background-color: var(--soft-bg); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }

        /*Sidebar التعديلات الجديدة على الـ */
        .sidebar { 
            background: #1e2522; 
            min-height: 100vh; 
            color: white; 
            padding-top: 20px; 
            position: fixed; 
            right: 0; 
            width: inherit; 
            z-index: 1000; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: -4px 0 15px rgba(0,0,0,0.05);
        }

        .main-content { 
            margin-right: 16.666667%; 
        } 

        /* بطاقات إحصائية عصرية */
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.03); 
            transition: 0.3s; 
        }

        .card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 12px 25px rgba(0,0,0,0.08);
        }

        .table thead { 
            background-color: var(--primary-green); 
            color: white; 
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        /* أزرار الحذف والتحكم */
        .btn-delete { 
            color: #d63031; 
            cursor: pointer; 
            transition: 0.3s; 
            text-decoration: none;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 8px;
        }

        .btn-delete:hover { 
            color: white; 
            background-color: #d63031;
        }

        /* روابط القائمة الجانبية */
        .nav-link-custom { 
            color: #b3c6bd; 
            text-decoration: none; 
            padding: 14px 20px; 
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.3s; 
            border-radius: 10px; 
            margin: 8px 15px; 
            cursor: pointer; 
            font-weight: 500;
        }

        .nav-link-custom i {
            font-size: 1.1rem;
        }

        .nav-link-custom:hover { 
            background: rgba(82, 183, 136, 0.1); 
            color: var(--light-green); 
        }

        .nav-link-custom.active { 
            background: var(--primary-green); 
            color: white;
            font-weight: bold;
        }

        .stat-icon { 
            font-size: 2.8rem; 
            opacity: 0.15; 
            position: absolute; 
            left: 20px; 
            top: 20px; 
        }

        .badge-role {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        /* تخصيص أشرطة التحميل */
        .progress {
            background-color: #e9ecef;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-2 sidebar p-0">
            <div>
                <div class="text-center my-4">
                    <h3 class="fw-bold text-success m-0"><i class="fas fa-tractor me-2"></i> AgroDz</h3>
                    <small class="text-muted" style="font-size: 11px; letter-spacing: 1px;">لوحة الإدارة الإستراتيجية</small>
                </div>
                <hr style="background-color: rgba(255,255,255,0.1); margin: 0 15px;">
                
                <div class="nav flex-column mt-3">
                    <a onclick="showSection('users-section')" class="nav-link-custom active" id="btn-users">
                        <i class="fas fa-users"></i> إدارة المستخدمين
                    </a>
                    <a onclick="showSection('stats-section')" class="nav-link-custom" id="btn-stats">
                        <i class="fas fa-chart-line"></i> الإحصائيات والأرباح
                    </a>
                </div>
            </div>

            <div class="mb-4 px-3 w-100 flex-column d-flex gap-2">
                <hr style="background-color: rgba(255,255,255,0.1);">
                <a href="/" class="btn btn-sm text-white w-100 py-2" style="background: rgba(255,255,255,0.05); border-radius: 10px;">
                    <i class="fas fa-globe me-1"></i> العودة للموقع
                </a>
                
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger w-100 py-2" style="border-radius: 10px; background-color: #c0392b; border: none;">
                        <i class="fas fa-sign-out-alt me-1"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-10 main-content p-5">
            <div class="d-flex justify-content-between align-items-center mb-5 bg-white p-4 rounded-4 shadow-sm">
                <div>
                    <h2 class="fw-bold text-dark m-0" id="page-title" style="font-size: 26px;">إدارة المستخدمين</h2>
                    <p class="text-muted small m-0 mt-1">مرحباً بك في وحدة التحكم الكاملة بالنظام</p>
                </div>
                <div class="fs-5">
                    مرحباً بك، <span class="badge bg-success p-2 px-3 fs-6 shadow-sm">{{ auth()->user()->name }}</span>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm p-3 mb-4" style="border-radius: 12px; border-right: 5px solid #2ecc71 !important;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div id="users-section">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="m-0 text-secondary font-weight-bold"><i class="fas fa-list-ul text-success me-2"></i> جميع المسجلين داخل التطبيق</h5>
                        <span class="badge bg-dark text-white rounded-pill px-3 py-2">الإجمالي: {{ $users->count() }} مستخدم</span>
                    </div>
                    <table class="table table-hover align-middle text-center table-borderless">
                        <thead>
                            <tr class="shadow-sm">
                                <th class="py-3">الاسم</th>
                                <th class="py-3">البريد الإلكتروني</th>
                                <th class="py-3">الدور (Role)</th>
                                <th class="py-3">تاريخ الانضمام</th>
                                <th class="py-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-secondary">
                            @foreach($users as $user)
                            <tr style="border-bottom: 1px solid #f1f3f5;">
                                <td class="py-3"><strong>{{ $user->name }}</strong></td>
                                <td class="py-3 text-muted">{{ $user->email }}</td>
                                <td class="py-3">
                                    @if($user->role == 'admin')
                                        <span class="badge-role bg-danger text-white"><i class="fas fa-shield-alt me-1"></i> مدير النظام</span>
                                    @elseif($user->role == 'eleveur')
                                        <span class="badge-role bg-success text-white"><i class="fas fa-cow me-1"></i> فلاح</span>
                                    @elseif($user->role == 'veterinaire')
                                        <span class="badge-role bg-primary text-white"><i class="fas fa-user-md me-1"></i> بيطري</span>
                                    @else
                                        <span class="badge-role bg-warning text-dark"><i class="fas fa-truck me-1"></i> موزع</span>
                                    @endif
                                </td>
                                <td class="py-3 text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="py-3">
                                    @if($user->id !== auth()->id()) 
                                    <a href="{{ url('admin/delete/'.$user->id) }}" 
                                       class="btn-delete" 
                                       onclick="return confirm('هل أنت متأكد من حذف هذا الحساب نهائياً؟')">
                                        <i class="fas fa-trash-alt me-1"></i> حذف الحساب
                                    </a>
                                    @else
                                    <span class="text-muted small italic bg-light p-2 rounded"><i class="fas fa-user-lock me-1"></i> أنت حالياً هنا</span>
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
                            <h6 class="text-muted small font-weight-bold">إجمالي المداخيل (Revenue)</h6>
                            <h2 class="fw-bold text-success my-2">{{ number_format($totalRevenue, 2) }} د.ج</h2>
                            <small class="text-muted">مستمدة من المعاملات المكتملة</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-white p-4 position-relative border-start border-primary border-5">
                            <i class="fas fa-shopping-cart stat-icon text-primary"></i>
                            <h6 class="text-muted small font-weight-bold">عدد الطلبات الكلي</h6>
                            <h2 class="fw-bold text-primary my-2">{{ $ordersCount }}</h2>
                            <small class="text-muted text-decoration-underline">طلبيات الأدوية والأعلاف</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-white p-4 position-relative border-start border-warning border-5">
                            <i class="fas fa-stethoscope stat-icon text-warning"></i>
                            <h6 class="text-muted small font-weight-bold">الاستشارات النشطة</h6>
                            <h2 class="fw-bold text-warning my-2">{{ $activeConsultations }}</h2> 
                            <small class="text-muted text-decoration-underline">تفاعل البياطرة حالياً</small>
                        </div>
                    </div>
                </div>

                <div class="card mt-5 p-4 border-0 shadow-sm">
                    <h4 class="text-dark fw-bold mb-2" style="font-size: 18px;">تحليل نمو المنصة التراكمي</h4>
                    <p class="text-muted small mb-4">النمو المحقق بالاعتماد على التفاعل الحالي للأدوار</p>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>معدل نمو أعداد المشتركين</span>
                            <strong>{{ $users->count() }} مستخدم</strong>
                        </div>
                        <div class="progress" style="height: 16px; border-radius: 20px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ min(100, $users->count() * 5) }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>نسبة الطلبات التجارية والـ B2B المكتملة</span>
                            <strong>{{ $ordersCount }} طلب</strong>
                        </div>
                        <div class="progress" style="height: 16px; border-radius: 20px;">
                            <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ min(100, $ordersCount * 4) }}%"></div>
                        </div>
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