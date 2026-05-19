<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - سجل الاستشارات الطبية</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <style>
        :root { 
            --primary: #14532d; 
            --primary-light: #f0fdf4; 
            --accent: #16a34a; 
            --bg: #f8fafc;
            --dark-text: #1e293b;
        }
        
        body { 
            background: var(--bg); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            text-align: right;
            color: var(--dark-text);
        }

        /* حاوية الجدول الاحترافية */
        .table-responsive-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            padding: 10px;
        }

        .custom-table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .custom-table thead th {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
            border-bottom: 2px solid #e2e8f0;
            padding: 15px 20px;
            white-space: nowrap;
        }

        .custom-table tbody tr {
            transition: all 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background-color: rgba(240, 253, 244, 0.5);
        }

        .custom-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        /* شارات الحالات المخصصة للجدول */
        .status-badge {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .badge-pending { background-color: #fef3c7; color: #b45309; }
        .badge-confirmed { background-color: #dcfce7; color: #15803d; }
        .badge-declined { background-color: #fee2e2; color: #b91c1c; }

        /* شارات الحيوانات */
        .animal-tag { 
            background: var(--primary-light); 
            color: var(--primary); 
            padding: 4px 10px; 
            border-radius: 6px; 
            font-size: 12px; 
            font-weight: 600; 
            border: 1px solid rgba(22, 163, 74, 0.1);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .code-sub-badge {
            background: white;
            color: #475569;
            font-size: 11px;
            padding: 1px 4px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
        }

        /* صندوق اتخاذ القرار داخل الجدول */
        .action-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 10px;
            max-width: 280px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0"><i class="fas fa-notes-medical text-success me-2"></i> سجل الاستشارات الطبية</h2>
            <p class="text-muted small mb-0 mt-1">تتبع مواعيدك وحالات الفحص البيطري الخاصة بمواشيك عبر منصة AgroDz</p>
        </div>
        <a href="{{ route('eleveur.dashboard') }}" class="btn btn-outline-success rounded-pill px-4 fw-bold">
            <i class="fas fa-arrow-right me-1"></i> العودة للرئيسية
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3 p-3 d-flex align-items-center">
            <i class="fas fa-check-circle me-2 fa-lg"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="table-responsive-container">
        <table class="table custom-table table-hover">
            <thead>
                <tr>
                    <th scope="col" style="width: 80px;">رقم الطلب</th>
                    <th scope="col">تاريخ الطلب</th>
                    <th scope="col">الطبيب المعالج</th>
                    <th scope="col">الحيوانات المعنية</th>
                    <th scope="col" style="width: 25%;">وصف الحالة / السبب</th>
                    <th scope="col">حالة الموعد والقرار</th>
                </tr>
            </thead>
            <tbody>
                @forelse($consultations->groupBy(function($item) { return $item->date_demande .'-'. $item->motif; }) as $group)
                    @php 
                        // أخذ السطر الأول للمعلومات الأساسية
                        $firstCon = $group->first(); 
                        
                        // التقاط السطر الذي يحتوي على التوقيت الفعلي المحدث من الطبيب
                        $consultationWithDate = $group->first(function($item) {
                            return !is_null($item->date_consultation);
                        });
                    @endphp
                    <tr>
                        <td class="fw-bold text-secondary">#{{ $firstCon->id }}</td>
                        
                        <td class="small text-muted">
                            <i class="far fa-calendar me-1"></i>
                            {{ $firstCon->date_demande ?? $firstCon->created_at->format('Y-m-d') }}
                        </td>
                        
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="fas fa-user-md fa-sm"></i>
                                </div>
                                <div>
                                    <span class="fw-bold d-block text-dark">د. {{ $firstCon->veterinaire->name }}</span>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt fa-xs"></i> {{ $firstCon->veterinaire->address ?? 'غير محدد' }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <div class="d-flex flex-wrap gap-1" style="max-width: 200px;">
                                @foreach($group as $item)
                                    @if($item->animal)
                                        <div class="animal-tag">
                                            <i class="fas fa-paw fa-xs"></i> {{ $item->animal->type }}
                                            @if($item->animal->identification_code)
                                                <span class="code-sub-badge">{{ $item->animal->identification_code }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        
                        <td class="text-secondary small">{{ $firstCon->motif }}</td>
                        
                        <td>
                            {{-- الحالة 1: الفلاح وافق مسبقاً وتأكد الموعد (الحالة أصبحت accepted في قاعدة البيانات لكي تظهر مقبولة عند الطبيب) --}}
                            @if($firstCon->status == 'accepted')
                                <div class="d-flex flex-column gap-1">
                                    <span class="status-badge badge-confirmed">
                                        <i class="fas fa-check-circle"></i> موعد مؤكد ومثبت
                                    </span>
                                    <small class="text-muted px-2" style="font-size: 11px;">
                                        <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($consultationWithDate ? $consultationWithDate->date_consultation : $firstCon->date_consultation)->format('Y-m-d H:i') }}
                                    </small>
                                </div>

                            {{-- الحالة 2: الفلاح أو الطبيب قام بالرفض --}}
                            @elseif($firstCon->status == 'declined')
                                <span class="status-badge badge-declined">
                                    <i class="fas fa-times-circle"></i> طلب مرفوض
                                </span>

                            {{-- الحالة 3: الطبيب حدد موعداً (يوجد تاريخ) ولكن الفلاح لم يضغط بعد (الحالة ما زالت pending) --}}
                            @elseif($consultationWithDate && $firstCon->status == 'pending')
                                <div class="action-box">
                                    <div class="small fw-bold text-primary mb-2">
                                        <i class="far fa-clock"></i> موعد مقترح من الطبيب: <br>
                                        <span class="text-dark">{{ \Carbon\Carbon::parse($consultationWithDate->date_consultation)->format('Y-m-d H:i') }}</span>
                                    </div>

                                    <div class="d-flex gap-1">
                                        <form action="{{ route('eleveur.consultations.confirm', $consultationWithDate->id) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="user_decision" value="confirmed">
                                            <button type="submit" class="btn btn-success btn-sm w-100 fw-bold py-1 rounded-2">قبول</button>
                                        </form>
                                        
                                        <form action="{{ route('eleveur.consultations.confirm', $consultationWithDate->id) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="user_decision" value="declined">
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-bold py-1 rounded-2" onclick="return confirm('هل أنت متأكد من رفض الموعد؟')">رفض</button>
                                        </form>
                                    </div>
                                </div>

                            {{-- الحالة 4: الطلب جديد تماماً وقيد الانتظار الأصلي قبل رد الطبيب --}}
                            @else
                                <span class="status-badge badge-pending">
                                    <i class="fas fa-hourglass-half"></i> في انتظار رد الطبيب
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="fas fa-calendar-times fs-2" style="opacity: 0.4;"></i></div>
                            <h5 class="text-muted">لا توجد استشارات طبية مسجلة حالياً</h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>