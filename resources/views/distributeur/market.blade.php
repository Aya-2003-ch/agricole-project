<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سوق الموزعين - AgroDz</title>
    <!-- Bootstrap CSS لنظام التنسيق -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-product { transition: transform 0.2s; border: none; border-radius: 15px; }
        .card-product:hover { transform: translateY(-5px); }
        .btn-order { background-color: #28a745; color: white; border-radius: 10px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success">🛒 سوق الأدوية والمواد الزراعية</h2>
        <a href="{{ route('distributeur.dashboard') }}" class="btn btn-outline-secondary">العودة للوحة التحكم</a>
    </div>

    <!-- رسائل النجاح أو الخطأ -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- محرك البحث -->
    <div class="card shadow-sm mb-5 p-3" style="border-radius: 20px;">
        <form action="{{ route('distributeur.market') }}" method="GET" class="row g-2">
            <div class="col-md-10">
                <input type="text" name="query" class="form-control form-control-lg border-0 bg-light" 
                       placeholder="ابحث عن دواء أو منتج (مثلاً: Amoxiciline)..." value="{{ request('query') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success btn-lg w-100">بحث</button>
            </div>
        </form>
    </div>

    <div class="row">
        @forelse($results as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm card-product">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title fw-bold text-dark">{{ $item->produit->nom }}</h5>
                            <span class="badge bg-warning text-dark">{{ $item->prix }} دج</span>
                        </div>
                        
                        <p class="text-muted small mb-2">
                            <i class="bi bi-person"></i> الموزع: <strong>{{ $item->distributeur->nom }}</strong>
                        </p>
                        
                        <div class="bg-light p-2 rounded mb-3 text-center">
                            <span class="text-secondary small">الكمية المتوفرة</span>
                            <h4 class="mb-0 fw-bold text-success">{{ $item->quantite }}</h4>
                        </div>

                        <ul class="list-unstyled small text-secondary">
                            <li>📅 تاريخ الانتهاء: {{ $item->date_exp }}</li>
                            <li>📍 الموقع: {{ $item->distributeur->localisation }}</li>
                        </ul>

                        <button class="btn btn-order w-100 mt-3 fw-bold" data-bs-toggle="modal" data-bs-target="#orderModal{{ $item->id }}">
                            إرسال طلب شراء
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal طلب الشراء -->
            <div class="modal fade" id="orderModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="border-radius: 20px;">
                        <form action="/distributeur/order" method="POST">
                            @csrf
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold">تأكيد طلب الشراء</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-center">أنت بصدد طلب <strong>{{ $item->produit->nom }}</strong> من الموزع <strong>{{ $item->distributeur->nom }}</strong></p>
                                
                                <!-- الحقول المخفية الضرورية -->
                                <input type="hidden" name="product_id" value="{{ $item->produit_id }}">
                                <input type="hidden" name="receiver_id" value="{{ $item->distributeur->user_id }}">

                                <div class="mb-3">
                                    <label class="form-label fw-bold">الكمية المطلوبة</label>
                                    <input type="number" name="quantity" class="form-control" placeholder="أدخل الكمية..." max="{{ $item->quantite }}" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">رقم الهاتف للتواصل</label>
                                    <input type="text" name="phone" class="form-control" placeholder="05XXXXXXXX" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">عنوان التوصيل</label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="اكتب العنوان بالتفصيل..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-success px-4">تأكيد وإرسال الطلب</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100" class="mb-3 opacity-50">
                <h4 class="text-muted">
                    @if(request('query'))
                        عذراً، لم نجد نتائج لـ "{{ request('query') }}"
                    @else
                        السوق فارغ حالياً أو ابدأ بالبحث عن منتجات
                    @endif
                </h4>
            </div>
        @endforelse
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>