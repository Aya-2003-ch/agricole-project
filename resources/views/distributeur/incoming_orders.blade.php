<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الطلبات الواردة | AgroDz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #344e41; 
            --primary-green: #588157;
            --light-bg: #f4f7f6;
        }
        body { 
            background-color: var(--light-bg); 
            font-family: 'Tajawal', sans-serif; 
            margin: 0; padding: 0;
        }
        
        .container-fluid { padding: 40px; max-width: 1300px; }
        
        .order-card {
            background: white; border-radius: 20px; padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-top: 5px solid var(--primary-green);
        }
        
        .table thead { background-color: #f8f9fa; }
        .table thead th { border-bottom: none; color: var(--primary-dark); padding: 15px; }

        .status-badge { padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 500; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-accepted { background: #d1e7dd; color: #0f5132; }
        .status-rejected { background: #f8d7da; color: #842029; }

        .role-badge {
            font-size: 12px; font-weight: 600; padding: 5px 12px;
            border-radius: 8px; display: inline-flex; align-items: center; gap: 5px;
        }

        .back-btn {
            width: 40px; height: 40px; border-radius: 12px; background-color: #fff;
            border: 1px solid #eee; display: flex; align-items: center; justify-content: center;
            color: var(--primary-green); text-decoration: none; transition: 0.3s;
        }
        .back-btn:hover { background-color: var(--primary-green); color: #fff; transform: scale(1.05); }
        
        .btn-action {
            width: 32px; height: 32px; border-radius: 8px; border: none;
            display: flex; align-items: center; justify-content: center;
            transition: 0.3s; color: white; font-size: 14px; cursor: pointer;
        }
        .btn-view { background-color: #3498db; }
        .btn-edit { background-color: #f39c12; }
        .btn-delete { background-color: #e74c3c; }
        .btn-action:hover { opacity: 0.8; transform: translateY(-2px); color: white; }

        @media print {
            body * { visibility: hidden; }
            #printSection, #printSection * { visibility: visible; }
            #printSection { 
                position: absolute; left: 0; top: 0; width: 100%; 
                padding: 20px; direction: rtl;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid mx-auto">
        <div class="order-card shadow border-0">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('distributeur.dashboard') }}" class="back-btn">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <div>
                        <h3 class="text-success fw-bold mb-0">قائمة الطلبات الواردة</h3>
                        <p class="text-muted small mb-0">إدارة طلبات الموزعين والأطباء البياطرة لمشروع AgroDz</p>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>المرسل</th>
                            <th>نوع الجهة</th> 
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold text-dark">{{ $order->produit?->nom ?? 'منتج غير متوفر' }}</td>
                            <td class="fw-medium text-secondary">{{ $order->sender->name }}</td>
                            <td>
                                @if($order->sender->role == 'veterinaire')
                                    <span class="role-badge bg-info-subtle text-info border border-info">
                                        <i class="fas fa-user-md"></i> طبيب بيطري
                                    </span>
                                @else
                                    <span class="role-badge bg-primary-subtle text-primary border border-primary">
                                        <i class="fas fa-truck-moving"></i> موزع
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="status-badge status-pending">قيد الانتظار</span>
                                @elseif($order->status == 'accepted')
                                    <span class="status-badge status-accepted">تم القبول</span>
                                @else
                                    <span class="status-badge status-rejected">مرفوض</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- زر عرض وتفاصيل -->
                                    <button class="btn-action btn-view" title="عرض الوصل"
                                            onclick="openOrderModal('{{ $order->produit?->nom }}', '{{ $order->quantity }}', '{{ $order->sender->name }}', '{{ $order->phone }}', '{{ $order->sender->address ?? 'غير مسجل' }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- زر تعديل -->
                                    <a href="{{ route('distributeur.order.edit', $order->id) }}" class="btn-action btn-edit" title="تعديل"><i class="fas fa-edit"></i></a>

                                    <!-- زر حذف (يفتح المودال) -->
                                    <button type="button" class="btn-action btn-delete" title="حذف"
                                            onclick="confirmDelete('{{ route('distributeur.order.destroy', $order->id) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-5 text-muted">لا توجد طلبات حالياً.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal التفاصيل والطباعة -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">تفاصيل وصل الطرد</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="printSection">
                    <div class="p-4 border rounded" style="border: 2px dashed #588157 !important; background-color: #fdfdfd;">
                        <div class="text-center mb-3">
                            <h3 class="fw-bold text-success mb-1">AgroDz</h3>
                            <small class="text-muted">منصة الإدارة الزراعية والبيطرية</small>
                        </div>
                        <hr>
                        <div class="mb-2"><strong>المنتج:</strong> <span id="m_product" class="text-dark"></span></div>
                        <div class="mb-2"><strong>الكمية:</strong> <span id="m_qty" class="badge bg-secondary"></span></div>
                        <div class="mb-2"><strong>المرسل إليه:</strong> <span id="m_name"></span></div>
                        <div class="mb-2"><strong>الهاتف:</strong> <span id="m_phone"></span></div>
                        <div class="mb-0"><strong>العنوان:</strong> <span id="m_address"></span></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-success px-4" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> طباعة الوصل
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal تأكيد الحذف الاحترافي -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-5">
                    <div class="text-danger mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x"></i>
                    </div>
                    <h4 class="fw-bold mb-2">تأكيد الحذف</h4>
                    <p class="text-muted">هل أنت متأكد من حذف هذا الطلب؟ لن تتمكن من استعادته لاحقاً.</p>
                    
                    <div class="d-flex gap-3 justify-content-center mt-4">
                        <form id="deleteForm" method="POST">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4 fw-bold">نعم، احذف</button>
                        </form>
                        <button type="button" class="btn btn-light px-4 fw-bold border" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // دالة فتح مودال التفاصيل
        function openOrderModal(product, qty, name, phone, address) {
            document.getElementById('m_product').innerText = product;
            document.getElementById('m_qty').innerText = qty;
            document.getElementById('m_name').innerText = name;
            document.getElementById('m_phone').innerText = phone;
            document.getElementById('m_address').innerText = address;
            new bootstrap.Modal(document.getElementById('orderModal')).show();
        }
        function confirmDelete(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteModal.show();
        }
    </script>
</body>
</html>