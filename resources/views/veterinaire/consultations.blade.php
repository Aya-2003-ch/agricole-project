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
        
        /* شارة الحيوان المعني */
        .animal-spec { 
            background: var(--primary-light); 
            color: var(--primary); 
            padding: 6px 12px; 
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
            <i class="fas fa-list-ol text-success me-1"></i> إجمالي الطلبات: 
            <strong class="text-success">
                {{ $consultations->groupBy(function($item) { 
                    return $item->eleveur_id . '-' . $item->created_at->format('Y-m-d H:i:s') . '-' . $item->motif; 
                })->count() }}
            </strong>
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
                        <th>الحيوانات المعنية بالفحص</th>
                        <th>تاريخ الطلب</th>
                        <th>سبب الاستشارة</th>
                        <th>الحالة</th>
                        <th>الموعد المحدد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations->groupBy(function($item) { 
                        return $item->eleveur_id . '-' . $item->created_at->format('Y-m-d H:i:s') . '-' . $item->motif; 
                    }) as $groupKey => $group)
                        @php 
                            $firstCon = $group->first(); 
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 15px;">{{ $firstCon->eleveur->name }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-phone-alt me-1" style="font-size: 11px;"></i> 
                                    {{ $firstCon->eleveur->phone_number ?? $firstCon->eleveur->phone ?? 'لا يوجد هاتف' }}
                                </small>
                            </td>
                            
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($group as $con)
                                        @if($con->animal)
                                            <div class="animal-spec">
                                                <i class="fas fa-paw fa-sm me-1"></i> {{ $con->animal->type }}
                                                @if($con->animal->identification_code)
                                                    <span class="badge bg-white text-dark border ms-1" style="font-size: 11px;">كود: {{ $con->animal->identification_code }}</span>
                                                @endif
                                                @if($con->animal->age)
                                                    <small class="text-muted ms-1" style="font-size: 11px;">({{ $con->animal->age }})</small>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach

                                    @if($group->whereNotNull('animal')->count() == 0)
                                        <span class="badge bg-secondary-subtle text-secondary p-2">غير محدد</span>
                                    @endif
                                </div>
                            </td>

                            <td class="text-muted" style="font-size: 13px;">
                                {{ $firstCon->created_at->format('Y-m-d H:i') }}
                            </td>
                            
                            <td style="max-width: 180px;">
                                <span class="text-truncate d-inline-block text-dark small" title="{{ $firstCon->motif }}" style="max-width: 160px; cursor: pointer;">
                                    {{ $firstCon->motif }}
                                </span>
                            </td>
                            
                            <td>
                                <span class="status-badge status-{{ $firstCon->status }}">
                                    @if($firstCon->status == 'pending') <i class="fas fa-clock"></i> قيد الانتظار
                                    @elseif($firstCon->status == 'accepted') <i class="fas fa-check-circle"></i> مقبولة
                                    @else <i class="fas fa-times-circle"></i> مرفوضة @endif
                                </span>
                            </td>
                            
                            <td>
                                @if($firstCon->date_consultation)
                                    <span class="badge bg-light text-primary border border-primary-subtle p-2 fw-bold" style="font-size: 12px;">
                                        <i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($firstCon->date_consultation)->format('Y-m-d H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted small">---</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
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