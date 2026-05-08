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
        .sidebar { background: #2d3436; min-height: 100vh; color: white; padding-top: 20px; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .table thead { background-color: #00b894; color: white; }
        .btn-delete { color: #d63031; cursor: pointer; transition: 0.3s; }
        .btn-delete:hover { color: #ff7675; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar text-center">
            <h3 class="fw-bold text-success mb-4">AgroDz</h3>
            <hr>
            <p><i class="fas fa-users"></i> إدارة المستخدمين</p>
            <p><i class="fas fa-chart-line"></i> الإحصائيات</p>
            <hr>
            <a href="/" class="btn btn-outline-light btn-sm">العودة للموقع</a>
        </div>

        <div class="col-md-10 p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">لوحة التحكم (المدير)</h2>
                <div class="text-muted">مرحباً بك، {{ auth()->user()->name }}</div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card p-4">
                <h4 class="mb-4 text-secondary">قائمة جميع المستخدمين</h4>
                <table class="table table-hover align-middle">
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
                                @if($user->id !== auth()->id()) <a href="{{ url('admin/delete/'.$user->id) }}" 
                                   class="btn-delete" 
                                   onclick="return confirm('هل أنت متأكد من حذف هذا الحساب نهائياً؟')">
                                    <i class="fas fa-trash-alt"></i> حذف
                                </a>
                                @else
                                <span class="text-muted">حسابك الحالي</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>