<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سوق الأدوية البيطرية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }
        .header-section {
            background: linear-gradient(135deg, #1e3799, #0c2461);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .search-bar {
            background: white;
            border-radius: 10px;
            padding: 5px;
            display: flex;
        }
        .search-bar input {
            border: none;
            box-shadow: none !important;
        }
        .medicine-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
        }
        .medicine-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        }
        .price-tag {
            color: #27ae60;
            font-size: 1.4rem;
            font-weight: bold;
        }
        .distributor-name {
            color: #4b6584;
            font-weight: 600;
        }
        .btn-order {
            border-radius: 8px;
            font-weight: bold;
            padding: 10px;
        }
        .modal-content {
            border-radius: 20px;
            border: none;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="header-section shadow-sm text-center">
        <h2 class="mb-3"><i class="fas fa-clinic-medical me-2"></i> سوق الأدوية البيطرية</h2>
        <p class="opacity-75">ابحث عن الأدوية المتوفرة لدى الموزعين المعتمدين وقم بطلبها مباشرة</p>
        
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <form action="" method="GET" class="search-bar shadow-sm">
                    <input type="text" name="query" class="form-control" placeholder="ابحث عن اسم الدواء (مثلاً: أوجمنتين، فيتامين...)" value="{{ $searchQuery ?? '' }}">
                    <button class="btn btn-success px-4" style="border-radius: 8px;">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($results as $item)
        <div class="col-md-4 mb-4">
            <div class="card h-100 medicine-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-light text-success border border-success">متوفر الآن</span>
                        <span class="price-tag">{{ $item->prix }} د.ج</span>
                    </div>
                    
                    <h5 class="card-title fw-bold mb-3 text-dark">{{ $item->medicine_name }}</h5>
                    
                    <div class="info-box bg-light p-3 rounded-3 mb-4">
                        <p class="mb-1 small text-muted"><i class="fas fa-truck-moving me-1 text-primary"></i> الموزع:</p>
                        <h6 class="distributor-name mb-2">{{ $item->distributeur_name }}</h6>
                        <p class="mb-0 small text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $item->distributeur_address }}</p>
                    </div>

                    <button class="btn btn-primary btn-order w-100 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal{{ $item->produit_id }}">
                        <i class="fas fa-cart-plus me-1"></i> إرسال طلب شراء
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal{{ $item->produit_id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">تأكيد طلب الشراء</h5>
                        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('veterinaire.order.store') }}" method="POST">
                        @csrf
                        <div class="modal-body text-center">
                            <i class="fas fa-info-circle text-info fa-3x mb-3"></i>
                            <p>أنت بصدد طلب <strong>{{ $item->medicine_name }}</strong></p>
                            <p class="text-muted small">سيتم إرسال هذا الطلب إلى الموزع: <strong>{{ $item->distributeur_name }}</strong></p>
                            
                            <input type="hidden" name="produit_id" value="{{ $item->produit_id }}">
                            <input type="hidden" name="receiver_id" value="{{ $item->distributeur_id }}">
                            
                            <div class="form-group mt-3 mx-auto" style="max-width: 200px;">
                                <label class="mb-2 fw-bold">الكمية المطلوبة:</label>
                                <input type="number" name="quantity" class="form-control text-center" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 8px;">إلغاء</button>
                            <button type="submit" class="btn btn-success px-4" style="border-radius: 8px;">تأكيد الإرسال</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="display-1 text-muted opacity-25"><i class="fas fa-box-open"></i></div>
            <h4 class="text-muted mt-3">لم نجد أي أدوية مطابقة لبحثك..</h4>
        </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>