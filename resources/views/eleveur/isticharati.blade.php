<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - استشاراتي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        :root { --primary: #14532d; --accent: #16a34a; --bg: #f1f5f9; }
        body { background: var(--bg); font-family: 'Segoe UI', sans-serif; text-align: right; }
        .consultation-card { border: none; border-radius: 20px; background: white; transition: 0.3s; overflow: hidden; }
        .status-header { padding: 10px 20px; font-weight: bold; font-size: 14px; display: flex; justify-content: space-between; }
        
        /* حالات الاستشارة */
        .status-pending { background: #fef3c7; color: #92400e; } /* قيد الانتظار */
        .status-accepted { background: #dbeafe; color: #1e40af; } /* الطبيب اقترح موعداً */
        .status-confirmed { background: #dcfce7; color: #166534; } /* الفلاح وافق */
        .status-declined { background: #fee2e2; color: #991b1b; } /* الفلاح رفض */

        .doctor-info { border-bottom: 1px id #f1f5f9; padding-bottom: 15px; margin-bottom: 15px; }
        .decision-box { background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 15px; padding: 20px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold text-dark"><i class="fas fa-calendar-check text-success me-2"></i> استشاراتي الطبية</h2>
        <a href="{{ route('eleveur.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">العودة للرئيسية</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($consultations as $con)
        <div class="col-md-6 mb-4">
            <div class="card consultation-card shadow-sm h-100">
                <!-- شريط الحالة العلوي -->
                <div class="status-header 
                    @if($con->status == 'pending') status-pending 
                    @elseif($con->status == 'accepted') status-accepted 
                    @elseif($con->status == 'confirmed') status-confirmed 
                    @else status-declined @endif">
                    <span>
                        <i class="fas fa-info-circle me-1"></i>
                        @if($con->status == 'pending') في انتظار رد الطبيب
                        @elseif($con->status == 'accepted') الطبيب حدد موعداً (ينتظر موافقتك)
                        @elseif($con->status == 'confirmed') موعد مؤكد ومثبت
                        @else موعد مرفوض @endif
                    </span>
                    <span>#{{ $con->id }}</span>
                </div>

                <div class="card-body p-4">
                    <div class="doctor-info d-flex align-items-center gap-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-user-md fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">دكتور: {{ $con->veterinaire->name }}</h5>
                            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $con->veterinaire->address ?? 'العنوان غير محدد' }}</small>
                        </div>
                    </div>

                    <p class="mb-3 text-secondary"><strong>وصف الحالة:</strong> {{ $con->motif }}</p>

                    {{-- الحالة 1: الطبيب اقترح موعداً والفلاح يجب أن يقرر --}}
                    @if($con->status == 'accepted')
                    <div class="decision-box">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-clock"></i> الموعد المقترح:</h6>
                        <div class="fs-4 fw-bold mb-3 text-dark">
                            {{ \Carbon\Carbon::parse($con->date_consultation)->translatedFormat('l j F Y') }} <br>
                            <span class="text-muted small">على الساعة: {{ \Carbon\Carbon::parse($con->date_consultation)->format('H:i') }}</span>
                        </div>
                        
                        @if($con->diagnostique)
                            <p class="small bg-white p-2 rounded border"><strong>ملاحظة الطبيب:</strong> {{ $con->diagnostique }}</p>
                        @endif

                        <div class="d-flex gap-2 mt-4">
                            <form action="{{ route('eleveur.consultations.confirm', $con->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <input type="hidden" name="user_decision" value="confirmed">
                                <button type="submit" class="btn btn-success w-100 fw-bold">قبول الموعد</button>
                            </form>
                            
                            <form action="{{ route('eleveur.consultations.confirm', $con->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <input type="hidden" name="user_decision" value="declined">
                                <button type="submit" class="btn btn-outline-danger w-100 fw-bold" onclick="return confirm('هل تريد رفض هذا الموعد؟')">رفض</button>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- الحالة 2: الموعد تم تأكيده نهائياً --}}
                    @if($con->status == 'confirmed')
                    <div class="alert alert-success border-0 py-3 mb-0">
                        <h6 class="fw-bold mb-1"><i class="fas fa-check-circle"></i> الموعد مثبت!</h6>
                        <p class="small mb-0">يرجى الحضور في الوقت المحدد: <strong>{{ \Carbon\Carbon::parse($con->date_consultation)->format('d/m/Y - H:i') }}</strong></p>
                    </div>
                    @endif

                    {{-- الحالة 3: الفلاح رفض الموعد --}}
                    @if($con->status == 'declined')
                    <div class="alert alert-danger border-0 py-3 mb-0">
                        <i class="fas fa-times-circle"></i> لقد قمت برفض هذا الموعد. سيتواصل معك الطبيب لاحقاً.
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">لا توجد استشارات مسجلة حالياً</h4>
        </div>
        @endforelse
    </div>
</div>

</body>
</html>