<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AgroDz - إدارة الاستشارات</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root {
    --primary-dark: #14532d;
    --accent-green: #16a34a;
    --bg-light: #f8fafc;
    --white: #ffffff;
    --shadow: 0 10px 25px rgba(0,0,0,0.05);
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--bg-light);
    margin: 0;
    padding: 20px;
    color: #1e293b;
}

.container {
    max-width: 900px;
    margin: 40px auto;
}

.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.title {
    font-size: 28px;
    font-weight: 800;
    color: var(--primary-dark);
    display: flex;
    align-items: center;
    gap: 10px;
}

/* بطاقة الاستشارة */
.card {
    background: var(--white);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: var(--shadow);
    border-right: 6px solid #e2e8f0;
    transition: 0.3s ease;
}

.card.pending {
    border-right-color: #f59e0b;
}

.card.completed {
    border-right-color: var(--accent-green);
}

.card-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.farmer-info h3 {
    margin: 0;
    color: var(--primary-dark);
    font-size: 18px;
}

.date-badge {
    font-size: 12px;
    background: #f1f5f9;
    padding: 5px 12px;
    border-radius: 20px;
    color: #64748b;
}

.motif-box {
    background: #fdfcfb;
    padding: 15px;
    border-radius: 12px;
    border: 1px dashed #cbd5e1;
    margin-bottom: 20px;
}

/* الفورم الداخلي */
.action-area {
    background: #f8fafc;
    padding: 20px;
    border-radius: 15px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #475569;
}

input, textarea, select {
    width: 100%;
    padding: 12px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    box-sizing: border-box;
    outline: none;
    transition: 0.3s;
}

input:focus, textarea:focus {
    border-color: var(--accent-green);
}

.full-width {
    grid-column: span 2;
}

.btn-confirm {
    grid-column: span 2;
    background: var(--accent-green);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s;
    margin-top: 10px;
}

.btn-confirm:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

/* نتيجة الاستشارة */
.result-box {
    background: #ecfdf5;
    padding: 15px;
    border-radius: 12px;
    border-right: 4px solid var(--accent-green);
}

.result-item {
    margin-bottom: 8px;
    font-size: 15px;
}

.result-item i {
    color: var(--accent-green);
    margin-left: 8px;
}

.back-link {
    text-decoration: none;
    color: #64748b;
    font-weight: 600;
    transition: 0.3s;
}

.back-link:hover {
    color: var(--primary-dark);
}
</style>
</head>

<body>

<div class="container">

<div class="header-section">
    <h1 class="title">
        <i class="fas fa-file-medical-alt"></i> إدارة الاستشارات
    </h1>

    <a href="{{ route('veterinaire.dashboard') }}" class="back-link">
        <i class="fas fa-arrow-right"></i> رجوع للوحة التحكم
    </a>
</div>

@forelse($consultations as $c)

<div class="card {{ $c->date_consultation ? 'completed' : 'pending' }}">

    <div class="card-header">
        <div class="farmer-info">
            <h3>
                <i class="fas fa-user-tag"></i>
                الفلاح: {{ $c->user->name ?? 'غير معروف' }}
            </h3>
        </div>

        <div class="date-badge">
            <i class="far fa-calendar-alt"></i>
            طلب في: {{ $c->created_at->format('Y-m-d') }}
        </div>
    </div>

    <div class="motif-box">
        <strong><i class="fas fa-comment-medical"></i> سبب الاستشارة:</strong>
        <p style="margin: 5px 0 0 0; color: #475569;">
            {{ $c->motif }}
        </p>
    </div>

    @if(!$c->date_consultation)

    <div class="action-area">

        <div>
            <label>📅 تاريخ المعاينة</label>
            <input type="date" id="date{{ $c->id }}" value="{{ date('Y-m-d') }}">
        </div>

        <div>
            <label>⚠️ درجة الخطورة</label>
            <select id="degree{{ $c->id }}">
                <option value="عادية">عادية</option>
                <option value="متوسطة">متوسطة</option>
                <option value="حرجة">حرجة / مستعجلة</option>
            </select>
        </div>

        <div class="full-width">
            <label>🩺 التشخيص النهائي والوصفة</label>
            <textarea id="diag{{ $c->id }}" rows="3" placeholder="اكتب التشخيص هنا..."></textarea>
        </div>

        <button class="btn-confirm" onclick="updateConsultation({{ $c->id }})">
            <i class="fas fa-check-circle"></i>
            تأكيد الحفظ والإرسال للفلاح
        </button>

    </div>

    @else

    <div class="result-box">
        <div class="result-item">
            <i class="fas fa-calendar-check"></i>
            <strong>موعد المعاينة:</strong>
            {{ $c->date_consultation }}
        </div>

        <div class="result-item">
            <i class="fas fa-Exclamation-circle"></i>
            <strong>الحالة:</strong>
            {{ $c->degree }}
        </div>

        <div class="result-item">
            <i class="fas fa-stethoscope"></i>
            <strong>التشخيص:</strong>
            {{ $c->diagnostique ?? 'لا يوجد تشخيص مسجل' }}
        </div>
    </div>

    @endif

</div>

@empty

<div style="text-align: center; padding: 50px; background: white; border-radius: 20px;">
    <i class="fas fa-folder-open" style="font-size: 50px; color: #cbd5e1;"></i>
    <p style="color: #64748b; margin-top: 15px;">
        لا توجد استشارات مسجلة حالياً.
    </p>
</div>

@endforelse

</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
function updateConsultation(id){

    let date = document.getElementById('date'+id).value;
    let degree = document.getElementById('degree'+id).value;
    let diag = document.getElementById('diag'+id).value;

    if(!date || !diag){
        alert("⚠️ يرجى ملء تاريخ المعاينة والتشخيص");
        return;
    }

    fetch(`/consultation/${id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            date_consultation: date,
            degree: degree,
            diagnostique: diag
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.id || data.success){
            alert("✔ تم تحديث بيانات الاستشارة بنجاح");
            location.reload();
        } else {
            alert("❌ فشل التحديث");
        }
    })
    .catch(err => {
        console.error(err);
        alert("❌ خطأ في السيرفر");
    });

}
</script>

</body>
</html>