<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سوق AgroDz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #1e2d24; 
            --main-bg: #f7f9f8;
            --primary-green: #5d8a66; 
            --card-radius: 20px;
        }

        body { 
            background-color: var(--main-bg); 
            font-family: 'Segoe UI', Tahoma, sans-serif;
            color: #333;
        }

        /* شريط بحث أصغر وأكثر بساطة */
        .search-wrapper {
            background: white;
            border-radius: 15px;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            margin-bottom: 40px;
        }

        .search-input-group {
            position: relative;
            background: #fcfdfd;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            display: flex;
            align-items: center;
            max-width: 600px; /* تحديد عرض شريط البحث */
            margin: 0 auto; /* توسيط شريط البحث */
        }
        
        .search-input-group input {
            border: none;
            background: transparent;
            padding: 10px 15px;
            width: 100%;
            outline: none;
            font-size: 0.95rem;
        }

        .btn-search {
            background-color: var(--primary-green);
            color: white;
            border-radius: 10px;
            padding: 8px 20px;
            border: none;
            font-weight: 600;
            margin: 4px;
            font-size: 0.9rem;
        }

        /* كروت المنتجات */
        .card-product {
            border: none;
            border-radius: var(--card-radius);
            background: white;
            transition: 0.3s ease;
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        }
        .card-product:hover { transform: translateY(-5px); }

        .price-badge {
            background: #f1f8f4;
            color: var(--primary-green);
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            background: #fbfcfb;
            padding: 8px 12px;
            border-radius: 12px;
        }

        .info-icon {
            width: 30px;
            height: 30px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            color: var(--primary-green);
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            font-size: 0.8rem;
        }

        .label-text { font-size: 0.7rem; color: #718096; display: block; }
        .value-text { font-size: 0.85rem; font-weight: 600; color: #2d3748; }

        .btn-order-now {
            background: var(--sidebar-bg);
            color: white;
            border-radius: 12px;
            padding: 10px;
            width: 100%;
            border: none;
            font-weight: bold;
            margin-top: 10px;
            transition: 0.3s;
            font-size: 0.95rem;
        }
        .btn-order-now:hover { background: var(--primary-green); }

        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .back-link { color: var(--primary-green); text-decoration: none; font-size: 0.9rem; font-weight: 600; }
    </style>
</head>
<body>

<div class="container py-5">
    
    <div class="header-nav">
        <h5 class="fw-bold m-0 text-dark">سوق المنتجات</h5>
        <a href="{{ route('distributeur.dashboard') }}" class="back-link">
            <i class="fas fa-arrow-right ms-1"></i> العودة  
        </a>
    </div>

    <div class="search-wrapper">
        <form action="{{ route('distributeur.market') }}" method="GET">
            <div class="search-input-group">
                <i class="fas fa-search text-muted ms-3"></i>
                <input type="text" name="query" placeholder="ابحث عن دواء..." value="{{ request('query') }}">
                <button type="submit" class="btn btn-search">بحث</button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 py-2 small">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($results as $item)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 card-product p-3 border-0">
                    <div class="card-body p-0 text-end">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold m-0">{{ $item->produit->nom }}</h6>
                            <span class="price-badge">{{ number_format($item->prix, 2) }} دج</span>
                        </div>

                        <div class="info-row">
                            <div class="info-icon"><i class="fas fa-truck-loading"></i></div>
                            <div>
                                <span class="label-text">الموزع</span>
                                <span class="value-text">{{ $item->distributeur->nom }}</span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon" style="color: #e53e3e;"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <span class="label-text">العنوان</span>
                                <span class="value-text">
                                    {{-- استخدام حقل address المصحح --}}
                                   {{ $item->distributeur->user->address ?? 'غير محدد' }}
                                </span>
                            </div>
                        </div>

                        <button class="btn btn-order-now" data-bs-toggle="modal" data-bs-target="#orderModal{{ $item->id }}">
                            إرسال طلب شراء
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="orderModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered shadow-sm">
                    <div class="modal-content border-0" style="border-radius: 20px;">
                        <form action="{{ route('distributeur.market.store') }}" method="POST">
                            @csrf
                            <div class="modal-header border-0">
                                <h6 class="fw-bold m-0">تأكيد طلب الشراء</h6>
                                <button type="button" class="btn-close small" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 pt-0">
                                <div class="mb-3 text-end">
                                    <label class="label-text mb-1">الكمية</label>
                                    <input type="number" name="quantity" class="form-control form-control-sm rounded-3" max="{{ $item->quantite }}" min="1" required>
                                </div>
                                <div class="mb-3 text-end">
                                    <label class="label-text mb-1">الهاتف</label>
                                    <input type="text" name="phone" class="form-control form-control-sm rounded-3" required>
                                </div>
                                <div class="mb-0 text-end">
                                    <label class="label-text mb-1">عنوان التوصيل</label>
                                    <textarea name="address" class="form-control form-control-sm rounded-3" rows="2" required></textarea>
                                </div>
                                <input type="hidden" name="product_id" value="{{ $item->produit_id }}">
                                <input type="hidden" name="receiver_id" value="{{ $item->distributeur->user_id }}">
                            </div>
                            <div class="modal-footer border-0 p-3 pt-0">
                                <button type="submit" class="btn btn-search w-100 py-2">تأكيد الطلب</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm border-0">
                <p class="text-muted small mb-0">لا توجد نتائج مطابقة لبحثك.</p>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>