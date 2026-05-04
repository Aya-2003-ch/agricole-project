<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>طلبات الاستشارة البيطرية | AgroDz</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
:root {
    --primary-green: #1b4332;
    --accent-green: #2d6a4f;
    --bg-light: #f8fafc;
    --text-dark: #1e293b;
    --text-gray: #64748b;
    --success-bg: #dcfce7;
    --success-text: #166534;
    --warning-bg: #fef3c7;
    --warning-text: #92400e;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: var(--bg-light);
    margin: 0;
    padding: 20px;
}

.main-container {
    max-width: 900px;
    margin: 0 auto;
}

.header-section {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
}

.header-section h2 {
    color: var(--primary-green);
    margin: 0;
}

/* تنسيق التنبيهات الجديدة لحالة الطلب */
.status-alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    border-right: 5px solid;
}

.alert-success {
    background: var(--success-bg);
    color: var(--success-text);
    border-right-color: #22c55e;
}

.alert-warning {
    background: var(--warning-bg);
    color: var(--warning-text);
    border-right-color: #f59e0b;
}

.request-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    margin-bottom: 40px;
    border-top: 5px solid var(--accent-green);
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-dark);
}

select, textarea {
    width: 100%;
    padding: 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-family: inherit;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

.btn-submit {
    background: var(--accent-green);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    width: 100%;
    font-size: 16px;
    transition: 0.3s;
}

.btn-submit:hover {
    background: var(--primary-green);
}

.history-title {
    color: var(--text-dark);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.consultation-item {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    border-right: 4px solid #cbd5e1;
}

.info-side h4 {
    margin: 0 0 5px 0;
    color: var(--primary-green);
}

.info-side p {
    margin: 0;
    font-size: 14px;
    color: var(--text-gray);
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.pending {
    background: var(--warning-bg);
    color: var(--warning-text);
}

.confirmed {
    background: var(--success-bg);
    color: var(--success-text);
}
</style>
</head>

<body>

<div class="main-container">

<div class="header-section">
    <i class="fas fa-user-md fa-2x" style="color: var(--accent-green);"></i>
    <h2>استشارة بيطرية ميدانية</h2>
</div>

<!-- قسم حالة الطلبات -->
<div class="status-tracker">
@foreach($consultations as $consult)
    <div class="status-alert {{ $consult->status == 'accepted' || $consult->status == 'confirmed' ? 'alert-success' : 'alert-warning' }}">
        
        @if($consult->status == 'accepted' || $consult->status == 'confirmed')
            <i class="fas fa-check-circle"></i>
            <span>
                وافق الدكتور <strong>{{ $consult->veterinaire->name ?? 'البيطري' }}</strong> على طلبك.
            </span>
        @else
            <i class="fas fa-clock"></i>
            <span>
                طلبك قيد الانتظار عند الدكتور <strong>{{ $consult->veterinaire->name ?? 'البيطري' }}</strong>.
            </span>
        @endif

    </div>
@endforeach
</div>

@if(session('success'))
<div class="status-alert alert-success" style="margin-top: 10px;">
    <i class="fas fa-check-double"></i>
    {{ session('success') }}
</div>
@endif

<!-- فورم طلب جديد -->
<div class="request-card">
    <h3 style="margin-top: 0;">
        <i class="fas fa-plus-circle"></i> طلب زيارة جديدة
    </h3>

    <form action="{{ route('eleveur.consultations.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>اختر الطبيب البيطري</label>
            <select name="veterinaire_id" required>
                <option value="">-- اختر طبيباً من القائمة --</option>

                @foreach($veterinaires as $vet)
                <option value="{{ $vet->id }}" {{ request('vet_id') == $vet->id ? 'selected' : '' }}>
                    د. {{ $vet->name }}
                </option>
                @endforeach

            </select>
        </div>

        <div class="form-group">
            <label>وصف الحالة</label>
            <textarea name="motif" required></textarea>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-paper-plane"></i> إرسال الطلب الآن
        </button>
    </form>
</div>

<!-- السجل -->
<h3 class="history-title">
    <i class="fas fa-history"></i> سجل طلباتي السابقة
</h3>

@forelse($consultations as $con)
<div class="consultation-item" style="border-right-color: {{ ($con->status == 'confirmed' || $con->status == 'accepted') ? '#22c55e' : '#f59e0b' }}">

    <div class="info-side">
        <h4>الطبيب: {{ $con->veterinaire->name ?? 'غير معروف' }}</h4>

        <p><strong>السبب:</strong> {{ Str::limit($con->motif, 60) }}</p>

        <p>
            <i class="far fa-calendar-alt"></i>
            {{ $con->created_at->format('Y-m-d') }}
        </p>

        @if($con->date_consultation)
        <p style="color: var(--accent-green); font-weight: bold;">
            <i class="fas fa-calendar-check"></i>
            {{ $con->date_consultation }}
        </p>
        @endif
    </div>

    <div class="status-side">
        <span class="status-badge {{ ($con->status == 'pending') ? 'pending' : 'confirmed' }}">
            {{ $con->status == 'pending' ? 'قيد الانتظار' : 'تم القبول' }}
        </span>
    </div>

</div>
@empty

<div style="text-align:center;padding:40px;background:white;border-radius:15px;">
    <p>لا توجد لديك استشارات</p>
</div>

@endforelse

</div>
</body>
</html>