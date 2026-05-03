<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل بيانات المنتج | AgroDz</title>
    <!-- استدعاء الأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #1b4332;
            --accent-green: #2d6a4f;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
            --text-gray: #64748b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-light);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-top: 5px solid var(--accent-green);
        }

        h2 {
            text-align: center;
            color: var(--primary-green);
            margin-bottom: 25px;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--text-gray);
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
        }

        input:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
            color: #94a3b8;
        }

        button {
            width: 100%;
            padding: 14px;
            background: var(--accent-green);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background: var(--primary-green);
            transform: translateY(-2px);
        }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            text-decoration: none;
            color: var(--text-gray);
            font-size: 14px;
            gap: 5px;
            transition: 0.2s;
        }

        .back-link:hover {
            color: var(--accent-green);
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-edit"></i> تعديل بيانات المنتج</h2>

    <!-- تنبيه بسيط في حال وجود أخطاء -->
    @if ($errors->any())
        <div style="background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('produits.update', $store->id) }}">
        @csrf
        @method('PUT')

        <div class="input-group">
            <label><i class="fas fa-tag"></i> اسم المنتج (لا يمكن تعديله)</label>
            <input type="text" value="{{ $store->produit->nom }}" disabled>
        </div>

        <div class="input-group">
            <label><i class="fas fa-boxes"></i> الكمية المتوفرة</label>
            <input type="number" name="quantite" value="{{ $store->quantite }}" placeholder="أدخل الكمية" required>
        </div>

        <div class="input-group">
            <label><i class="fas fa-money-bill-wave"></i> سعر الوحدة (د.ج)</label>
            <input type="number" name="prix" value="{{ $store->prix }}" placeholder="أدخل السعر" required>
        </div>

        <div class="input-group">
            <label><i class="fas fa-calendar-alt"></i> تاريخ نهاية الصلاحية</label>
            <input type="date" name="date_exp" value="{{ $store->date_exp }}" required>
        </div>

        <button type="submit">
            <i class="fas fa-save"></i> حفظ التعديلات
        </button>
    </form>

    <a href="{{ route('produits.index') }}" class="back-link">
        <i class="fas fa-chevron-right"></i> العودة للمخزن
    </a>
</div>

</body>
</html>