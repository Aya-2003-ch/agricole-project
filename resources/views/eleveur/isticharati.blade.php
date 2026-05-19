<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - استشاراتي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        :root { --primary: #14532d; --primary-light: #f0fdf4; --accent: #16a34a; --bg: #f1f5f9; }
        body { background: var(--bg); font-family: 'Segoe UI', sans-serif; text-align: right; }
        .consultation-card { border: none; border-radius: 20px; background: white; transition: 0.3s; overflow: hidden; }
        .status-header { padding: 10px 20px; font-weight: bold; font-size: 14px; display: flex; justify-content: space-between; }
        
        /* حالات الاستشارة */
        .status-pending { background: #fef3c7; color: #92400e; } /* قيد الانتظار */
        .status-accepted { background: #dbeafe; color: #1e40af; } /* الطبيب اقترح موعداً */
        .status-confirmed { background: #dcfce7; color: #166534; } /* الفلاح وافق */
        .status-declined { background: #fee2e2; color: #991b1b; } /* الفلاح رفض */

        .doctor-info { border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 15px; }
        .decision-box { background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 15px; padding: 20px; }
        
        /* شارة الحيوان المعني */
        .animal-badge { 
            background: var(--primary-light); 
            color: var(--primary); 
            padding: 4px 10px; 
            border-radius: 8px; 
            font-size: 12px; 
            font-weight: 700; 
            display: inline-flex;
            align-items: center;
            border: 1px solid rgba(22, 163, 74, 0.1);
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold text-dark"><i class="fas fa-calendar-check text-success me-2"></i> استشاراتي الطبية</h2>
        <a href="{{ route('eleveur.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">العودة للرئيسية</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3 p-3">{{ session('success') }}</div>
    @endif

    <div class="row">
        {{-- 🛠️ سحر التجميع: نقوم بدمج الأسطر حسب تاريخ الطلب وسبب الاستشارة ليظهر كطلب واحد --}}
        @forelse($consultations->groupBy(function($item) { return $item->date_demande .'-'. $item->motif; }) as $group)
            @php 
                // نأخذ السطر الأول لاستخراج البيانات الموحدة للطلب
                $firstCon = $group->first(); 
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card consultation-card shadow-sm h-100">
                    <div class="status-header 
                        @if($firstCon->status == 'pending') status-pending 
                        @elseif($firstCon->status == 'accepted') status-accepted 
                        @elseif($firstCon->status == 'confirmed') status-confirmed 
                        @else status-declined @endif">
                        <span>
                            <i class="fas fa-info-circle me-1"></i>
                            @if($firstCon->status == 'pending') في انتظار رد الطبيب
                            @elseif($firstCon->status == 'accepted') الطبيب حدد موعداً (ينتظر موافقتك)
                            @elseif($firstCon->status == 'confirmed') موعد مؤكد ومثبت
                            @else موعد مرفوض @endif
                        </span>
                        <span>#{{ $firstCon->id }}</span>
                    </div>

                    <div class="card-body p-4">
                        <div class="doctor-info d-flex align-items-center gap-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-user-md fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">دكتور: {{ $firstCon->veterinaire->name }}</h5>
                                <small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $firstCon->veterinaire->address ?? 'العنوان غير محدد' }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong class="text-dark d-block mb-2">الحيوانات المعنية بالفحص:</strong>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($group as $item)
                                    @if($item->animal)
                                        <div class="animal-badge">
                                            <i class="fas fa-paw fa-sm me-1"></i> {{ $item->animal->type }}
                                            @if($item->animal->identification_code)
                                                <span class="badge bg-white text-dark border ms-1" style="font-size: 10px;">كود: {{ $item->animal->identification_code }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <p class="mb-3 text-secondary"><strong>وصف الحالة والسبب:</strong> {{ $firstCon->motif }}</p>
                        
                        <p class="small text-muted mb-3"><i class="fas fa-clock me-1"></i> تاريخ إرسال الطلب: {{ $firstCon->date_demande ?? $firstCon->created_at->format('Y-m-d H:i') }}</p>

                        {{-- الحالة 1: الطبيب اقترح موعداً والفلاح يجب أن يقرر --}}
                        @if($firstCon->status == 'accepted')
                        <div class="decision-box">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-clock"></i> الموعد المقترح من الطبيب:</h6>
                            <div class="fs-4 fw-bold mb-3 text-dark">
                                {{ \Carbon\Carbon::parse($firstCon->date_consultation)->format('Y-m-d H:i') }} <br>
                                <span class="text-muted small">على الساعة: {{ \Carbon\Carbon::parse($firstCon->date_consultation)->format('H:i') }}</span>
                            </div>
                            
                            @if($firstCon->diagnostique)
                                <p class="small bg-white p-2 rounded border mb-3"><strong>ملاحظة الطبيب الأولية:</strong> {{ $firstCon->diagnostique }}</p>
                            @endif

                            <div class="d-flex gap-2 mt-4">
                                {{-- عند قبول الفلاح، نرسل المعرّف الرئيسي للطلب المجمع لتحديث كافة الأسطر المرتبطة به --}}
                                <form action="{{ route('eleveur.consultations.confirm', $firstCon->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="user_decision" value="confirmed">
                                    <button type="submit" class="btn btn-success w-100 fw-bold rounded-3 py-2">قبول الموعد</button>
                                </form>
                                
                                <form action="{{ route('eleveur.consultations.confirm', $firstCon->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="user_decision" value="declined">
                                    <button type="submit" class="btn btn-outline-danger w-100 fw-bold rounded-3 py-2" onclick="return confirm('هل تريد رفض هذا الموعد؟')">رفض</button>
                                </form>
                            </div>
                        </div>
                        @endif

                        {{-- الحالة 2: الموعد تم تأكيده نهائياً --}}
                        @if($firstCon->status == 'confirmed')
                        <div class="alert alert-success border-0 py-3 mb-0 rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1"><i class="fas fa-check-circle"></i> الموعد مثبت نهائياً!</h6>
                            <p class="small mb-0">يرجى انتظار الطبيب في الوقت المحدد: <strong>{{ \Carbon\Carbon::parse($firstCon->date_consultation)->format('d/m/Y - H:i') }}</strong></p>
                        </div>
                        @endif

                        {{-- الحالة 3: الفلاح رفض الموعد --}}
                        @if($firstCon->status == 'declined')
                        <div class="alert alert-danger border-0 py-3 mb-0 rounded-3 shadow-sm">
                            <i class="fas fa-times-circle me-1"></i> لقد قمت برفض هذا الموعد المقترح. 
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3"><i class="fas fa-calendar-times text-muted fs-1" style="opacity: 0.3;"></i></div>
                <h4 class="text-muted">لا توجد استشارات طبية مسجلة حالياً</h4>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>