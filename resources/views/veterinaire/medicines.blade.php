<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مخزن الأدوية - لوحة الطبيب</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background: #f4f7f6; padding: 30px; }
        .container { max-width: 1000px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn-add { background: #16a34a; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; transition: 0.3s; }
        .btn-add:hover { background: #15803d; }
        .med-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .med-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; position: relative; }
        .med-card i { font-size: 35px; color: #16a34a; margin-bottom: 10px; }
        .price { color: #14532d; font-weight: bold; margin-top: 10px; display: block; font-size: 18px; }
        .actions { margin-top: 15px; display: flex; justify-content: center; gap: 10px; border-top: 1px solid #eee; padding-top: 10px; }
        .btn-edit { color: #2563eb; text-decoration: none; font-size: 14px; }
        .btn-delete { color: #dc2626; background: none; border: none; cursor: pointer; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>💊 قائمة الأدوية المتوفرة</h2>
            <!-- ربط زر الإضافة بالمسار الذي عرفناه في web.php -->
            <a href="{{ route('veterinaire.produit.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> إضافة دواء جديد
            </a>
        </div>

        <div class="med-grid">
            @forelse($medicines as $med)
                <div class="med-card">
                    <i class="fas fa-pills"></i>
                    <h4>{{ $med->nom }}</h4>
                    <p style="font-size: 13px; color: #777; height: 40px; overflow: hidden;">{{ $med->description }}</p>
                    <span class="price">{{ $med->prix }} دج</span>
                    
                    <div class="actions">
                        <!-- زر التعديل -->
                        <a href="{{ route('veterinaire.produit.edit', $med->id) }}" class="btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>

                        <!-- زر الحذف -->
                        <form action="{{ route('veterinaire.produit.destroy', $med->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدواء؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: white; border-radius: 12px;">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" style="opacity: 0.5;">
                    <p style="margin-top: 15px; color: #666;">لا توجد أدوية في المخزن حالياً.</p>
                </div>
            @endforelse
        </div>
        
        <br>
        <hr style="border: 0; border-top: 1px solid #ddd;">
        <a href="{{ route('veterinaire.dashboard') }}" style="color: #666; text-decoration: none; font-size: 14px;">
            <i class="fas fa-arrow-right"></i> العودة للرئيسية
        </a>
    </div>
</body>
</html>