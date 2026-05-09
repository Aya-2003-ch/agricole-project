<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - إدارة الاستشارات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #14532d;
            --accent: #16a34a;
            --bg: #f8fafc;
        }
        body { background: var(--bg); font-family: 'Segoe UI', sans-serif; text-align: right; }
        
        .main-card { border: none; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: white; padding: 25px; }
        
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-accepted { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #991b1b; }

        .table thead { background: #f1f5f9; }
        .table th { border: none; color: #64748b; font-size: 14px; padding: 15px; }
        .table td { vertical-align: middle; padding: 15px; border-bottom: 1px solid #f1f5f9; }
        
        .btn-action { border-radius: 10px; padding: 8px 15px; font-size: 13px; font-weight: 600; transition: 0.3s; }
        
        /* Sidebar Mini Fix */
        .sidebar-mini { width: 260px; height: 100vh; background: var(--primary); position: fixed; right: 0; color: white; padding: 20px; }
        .content-area { margin-right: 260px; padding: 40px; }
        .sidebar-mini a { color: #ecfdf5; text-decoration: none; display: block; padding: 12px; border-radius: 10px; margin-bottom: 5px; }
        .sidebar-mini a:hover { background: var(--accent); }
    </style>
</head>
<body>

<div class="sidebar-mini">
    <h4 class="text-center mb-4 fw-bold">AgroDz 🌿</h4>
    <a href="{{ route('veterinaire.dashboard') }}"><i class="fas fa-th-large"></i> الرئيسية</a>
    <a href="#" style="background: var(--accent);"><i class="fas fa-stethoscope"></i> الاستشارات</a>
    <a href="{{ route('veterinaire.chats') }}"><i class="fas fa-comments"></i> الدردشة</a>
    <hr>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> خروج
    </a>
</div>

<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">إدارة استشارات الفلاحين</h2>
        <div class="text-muted">إجمالي الطلبات: {{ $consultations->count() }}</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4">{{ session('success') }}</div>
    @endif

    <div class="main-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>المربي (الفلاح)</th>
                        <th>تاريخ الطلب</th>
                        <th>سبب الاستشارة</th>
                        <th>الحالة</th>
                        <th>الموعد المحدد</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $con)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $con->eleveur->name }}</div>
                            <small class="text-muted"><i class="fas fa-phone-alt"></i> {{ $con->eleveur->phone ?? 'لا يوجد هاتف' }}</small>
                        </td>
                        <td>{{ $con->date_demande }}</td>
                        <td style="max-width: 200px;">
                            <span class="text-truncate d-inline-block" title="{{ $con->motif }}">{{ $con->motif }}</span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $con->status }}">
                                @if($con->status == 'pending') قيد الانتظار
                                @elseif($con->status == 'accepted') مقبولة
                                @else مرفوضة @endif
                            </span>
                        </td>
                        <td>
                            <span class="text-primary fw-bold">{{ $con->date_consultation ?? '---' }}</span>
                        </td>
                        <td>
                            @if($con->status == 'pending')
                                <button class="btn btn-action btn-success me-1" data-bs-toggle="modal" data-bs-target="#acceptModal{{ $con->id }}">
                                    <i class="fas fa-check"></i> قبول
                                </button>
                                <form action="{{ route('veterinaire.consultations.update', $con->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-action btn-outline-danger" onclick="return confirm('هل أنت متأكد من الرفض؟')">
                                        إلغاء
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">لا توجد إجراءات</span>
                            @endif
                        </td>
                    </tr>

                    <div class="modal fade" id="acceptModal{{ $con->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header bg-success text-white rounded-top-4">
                                    <h5 class="modal-title">تحديد موعد الاستشارة</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('veterinaire.consultations.update', $con->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="accepted">
                                    <div class="modal-body p-4 text-end">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">اختر تاريخ ووقت الموعد:</label>
                                            <input type="datetime-local" name="date_consultation" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">ملاحظات أولية (اختياري):</label>
                                            <textarea name="diagnostique" class="form-control" rows="3" placeholder="مثلاً: يرجى عزل الحيوان المريض حتى الموعد..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-success px-4 rounded-3 fw-bold">تأكيد الموعد وإرسال</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">لا توجد طلبات استشارة حالياً.</td>
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