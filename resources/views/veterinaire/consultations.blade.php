<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - إدارة الاستشارات </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #14532d;
            --primary-light: #f0fdf4;
            --accent: #16a34a;
            --bg: #f8fafc;
            --dark-blue: #0f172a;
            --text-muted: #64748b;
            --danger-light: #fef2f2;
        }
        
        body { 
            background: var(--bg); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            text-align: right; 
            color: var(--dark-blue);
        }
        
        /* Sidebar العصري */
        .sidebar-mini { 
            width: 280px; 
            height: 100vh; 
            background: linear-gradient(180deg, var(--primary) 0%, #052e16 100%); 
            position: fixed; 
            right: 0; 
            top: 0; 
            color: white; 
            padding: 25px 20px; 
            z-index: 1000;
            box-shadow: -4px 0 20px rgba(0,0,0,0.1);
        }
        .sidebar-mini a { 
            color: #bbf7d0; 
            text-decoration: none; 
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px; 
            border-radius: 12px; 
            margin-bottom: 8px; 
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .sidebar-mini a:hover, .sidebar-mini a.active { 
            background: rgba(255, 255, 255, 0.15); 
            color: white;
            transform: translateX(-5px);
        }

        /* منطقة المحتوى */
        .content-area { margin-right: 280px; padding: 45px; }
        
        /* البطاقة الرئيسية للجدول */
        .main-card { 
            border: none; 
            border-radius: 24px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.02); 
            background: white; 
            padding: 30px; 
            border: 1px solid rgba(0,0,0,0.03);
        }
        
        /* تنسيق الجدول الاحترافي */
        .table thead { background: #f8fafc; }
        .table th { 
            border: none; 
            color: var(--text-muted); 
            font-size: 13px; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 18px 15px; 
            font-weight: 600;
        }
        .table td { vertical-align: middle; padding: 18px 15px; border-bottom: 1px solid #f1f5f9; }
        .table tbody tr:hover { background-color: #fafafa; }
        
        /* شارات الحالة الملونة */
        .status-badge { padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-accepted { background: #dcfce7; color: #15803d; }
        .status-rejected { background: var(--danger-light); color: #b91c1c; }

        /* الأزرار المطورة */
        .btn-action { 
            border-radius: 10px; 
            padding: 8px 14px; 
            font-size: 13px; 
            font-weight: 600; 
            transition: all 0.2s ease-in-out; 
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        
        /* شارة الحيوان المعني */
        .animal-spec { 
            background: var(--primary-light); 
            color: var(--primary); 
            padding: 5px 12px; 
            border-radius: 8px; 
            font-size: 13px; 
            font-weight: 700; 
            display: inline-block;
            border: 1px solid rgba(22, 163, 74, 0.1);
        }
    </style>
</head>
<body>

<div class="sidebar-mini">
    <h3 class="text-center mb-5 fw-bold text-white">AgroDz 🌿</h3>
    <a href="{{ route('veterinaire.dashboard') }}"><i class="fas fa-th-large"></i> الرئيسية</a>
    <a href="#" class="active"><i class="fas fa-stethoscope"></i> الاستشارات</a>
    <a href="{{ route('veterinaire.chats') }}"><i class="fas fa-comments"></i> الدردشة</a>
    <hr style="opacity: 0.2;">
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #fca5a5;">
        <i class="fas fa-sign-out-alt"></i> خروج
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</div>

<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--dark-blue);">إدارة استشارات الفلاحين</h2>
            <p class="text-muted small mb-0">تابع وقم بإدارة طلبات الفحص الصحي لقطعان المربين</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-4 shadow-sm border font-weight-bold" style="font-size: 14px;">
            <i class="fas fa-list-ol text-success me-1"></i> إجمالي الطلبات: <strong class="text-success">{{ $consultations->count() }}</strong>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 p-3 d-flex align-items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="main-card">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>المربي (الفلاح)</th>
                        <th>الحيوان المعني</th>
                        <th>تاريخ الطلب</th>
                        <th>سبب الاستشارة</th>
                        <th>الحالة</th>
                        <th>الموعد المحدد</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $con)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 15px;">{{ $con->eleveur->name }}</div>
                            <small class="text-muted"><i class="fas fa-phone-alt me-1" style="font-size: 11px;"></i> {{ $con->eleveur->phone ?? 'لا يوجد هاتف' }}</small>
                        </td>
                        
                        <td>
                            <span class="animal-spec">
                                <i class="fas fa-paw fa-sm me-1"></i> {{ $con->animal->type ?? 'غير محدد' }}
                            </span>
                            @if($con->animal && $con->animal->identification_code)
                                <div class="small text-secondary mt-1" style="font-size: 12px;">
                                    <strong>الكود:</strong> <code class="text-dark">{{ $con->animal->identification_code }}</code>
                                </div>
                            @endif
                        </td>

                        <td class="text-muted" style="font-size: 13px;">
                            {{ $con->date_demande ?? $con->created_at->format('Y-m-d') }}
                        </td>
                        
                        <td style="max-width: 180px;">
                            <span class="text-truncate d-inline-block text-dark small" title="{{ $con->motif }}" style="max-width: 160px; cursor: pointer;">
                                {{ $con->motif }}
                            </span>
                        </td>
                        
                        <td>
                            <span class="status-badge status-{{ $con->status }}">
                                <span class="spinner-grow spinner-grow-sm d-none" role="status"></span>
                                @if($con->status == 'pending') <i class="fas fa-clock"></i> قيد الانتظار
                                @elseif($con->status == 'accepted') <i class="fas fa-check-circle"></i> مقبولة
                                @else <i class="fas fa-times-circle"></i> مرفوضة @endif
                            </span>
                        </td>
                        
                        <td>
                            @if($con->date_consultation)
                                <span class="badge bg-light text-primary border border-primary-subtle p-2 fw-bold" style="font-size: 12px;">
                                    <i class="fas fa-calendar-alt me-1"></i> {{ $con->date_consultation }}
                                </span>
                            @else
                                <span class="text-muted small">---</span>
                            @endif
                        </td>
                        
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                @if($con->status == 'pending')
                                    <button class="btn btn-action btn-success text-white" data-bs-toggle="modal" data-bs-target="#acceptModal{{ $con->id }}">
                                        <i class="fas fa-check"></i> قبول
                                    </button>
                                    
                                    <form action="{{ route('veterinaire.consultations.update', $con->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-action btn-outline-warning" onclick="return confirm('هل أنت متأكد من رفض هذا الطلب؟')">
                                            رفض
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('veterinaire.consultations.destroy', $con->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-outline-danger" title="حذف الطلب نهائياً" onclick="return confirm('⚠️ تحذير: هل أنتِ متأكدة من حذف طلب الاستشارة هذا نهائياً من النظام؟')">
                                        <i class="fas fa-trash-alt"></i> حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="acceptModal{{ $con->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <div class="modal-header bg-success text-white rounded-top-4 py-3">
                                    <h5 class="modal-title fw-bold"><i class="fas fa-calendar-plus me-2"></i> تحديد موعد الاستشارة</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('veterinaire.consultations.update', $con->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="accepted">
                                    <div class="modal-body p-4 text-end">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-secondary">اختر تاريخ ووقت الموعد للمربي:</label>
                                            <input type="datetime-local" name="date_consultation" class="form-control form-control-lg rounded-3" style="font-size: 15px;" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-secondary">ملاحظات أو توجيهات أولية (اختياري):</label>
                                            <textarea name="diagnostique" class="form-control rounded-3" rows="3" placeholder="يرجى عزل الحيوانات المصابة حتى وصول الطبيب..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 bg-light rounded-bottom-4 p-3">
                                        <button type="button" class="btn btn-light px-3 rounded-3" data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-success px-4 rounded-3 fw-bold shadow-sm">تأكيد الموعد وإرسال</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div class="mb-2"><i class="fas fa-folder-open fs-1 text-secondary" style="opacity: 0.4;"></i></div>
                            لا توجد طلبات استشارة واردة حالياً.
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