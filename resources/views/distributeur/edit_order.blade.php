<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الطلب | AgroDz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #1e2d24; 
            --primary-green: #588157;
            --light-bg: #f4f7f6;
        }
        body { background-color: var(--light-bg); font-family: 'Tajawal', sans-serif; }
        .edit-card {
            max-width: 650px; margin: 40px auto; background: white;
            border-radius: 20px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-top: 5px solid var(--primary-green);
        }
        .form-label { font-weight: bold; color: var(--primary-dark); font-size: 14px; margin-bottom: 8px; }
        .input-group-text { background-color: #f8f9fa; color: var(--primary-green); border-left: none; }
        .form-control:focus { border-color: var(--primary-green); box-shadow: 0 0 0 0.2rem rgba(88, 129, 87, 0.25); }
        .btn-update { background-color: var(--primary-green); color: white; border-radius: 10px; padding: 12px; border: none; transition: 0.3s; width: 100%; }
        .btn-update:hover { background-color: #3a5a40; transform: translateY(-2px); }
        .back-link { text-decoration: none; color: #636e72; font-size: 14px; transition: 0.3s; }
        .back-link:hover { color: var(--primary-green); }
        .section-title { font-size: 12px; text-transform: uppercase; color: #a0aec0; letter-spacing: 1px; margin-bottom: 15px; border-bottom: 1px solid #edf2f7; padding-bottom: 5px; }
    </style>
</head>
<body>

<div class="container">
    <div class="edit-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-success m-0">تعديل تفاصيل الطلب</h4>
                <small class="text-muted">رقم الطلب: #{{ $order->id }}</small>
            </div>
            <a href="{{ route('distributeur.incoming.orders') }}" class="back-link">
                <i class="fas fa-arrow-right ml-1"></i> العودة للطلبات
            </a>
        </div>

        <form action="{{ route('distributeur.order.update', $order->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- قسم معلومات المرسل (للقراءة فقط) -->
            <div class="section-title">معلومات المرسل</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم المرسل</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control bg-light" value="{{ $order->sender->name }}" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">العنوان</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" class="form-control bg-light" value="{{ $order->sender->address ?? 'غير متوفر' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- قسم تفاصيل المنتج والطلب -->
            <div class="section-title mt-2">تفاصيل المنتج والطلب</div>
            <div class="mb-3">
                <label class="form-label">المنتج المطلوب</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-box"></i></span>
                    <input type="text" class="form-control bg-light" value="{{ $order->produit?->nom }}" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">الكمية (Quantité)</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $order->quantity }}" required min="1">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">رقم هاتف التواصل</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ $order->phone }}" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="status" class="form-label">حالة الطلب</label>
                <select name="status" id="status" class="form-select">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ قيد الانتظار</option>
                    <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>✅ مقبول</option>
                    <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>❌ مرفوض</option>
                </select>
            </div>

            <button type="submit" class="btn-update fw-bold">
                <i class="fas fa-save me-1"></i> حفظ التعديلات
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>