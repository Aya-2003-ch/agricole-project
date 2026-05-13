<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - التبليغ عن وباء</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            --warning-red: #dc2626;
            --primary-dark: #14532d;
            --accent-green: #16a34a;
            --bg-light: #f1f5f9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .report-container {
            max-width: 700px;
            width: 90%;
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 10px solid var(--warning-red);
            margin: 20px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .report-header i {
            font-size: 50px;
            color: var(--warning-red);
            margin-bottom: 15px;
        }

        .report-header h1 {
            color: var(--primary-dark);
            font-size: 24px;
            margin: 0;
        }

        .alert-banner {
            background: #fff1f2;
            border-right: 5px solid var(--warning-red);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            color: #991b1b;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary-dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            box-sizing: border-box;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--warning-red);
            outline: none;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .btn-submit {
            background: var(--warning-red);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-submit:hover {
            background: #b91c1c;
            transform: translateY(-2px);
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-back:hover {
            color: var(--primary-dark);
        }
    </style>
</head>
<body>

    <div class="report-container">
        <div class="report-header">
            <i class="fas fa-biohazard"></i>
            <h1>تبليغ فوري عن وباء</h1>
            <p style="color: #64748b;">نظام AgroDz لحماية الثروة الحيوانية</p>
        </div>

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="alert-banner">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>تنبيه:</strong> سيتم إرسال هذا التقرير تلقائياً إلى المصالح الفلاحية المعنية.
        </div>

        <form action="{{ route('veterinaire.report.send') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>اسم المرض المشتبه به:</label>
                <!-- التعديل: nom_maladie مطابق للموديل -->
                <input type="text" name="nom_maladie" class="form-control" placeholder="مثال: الجلد العقدي، طاعون المجترات..." required>
            </div>

            <div class="form-group">
                <label>المنطقة / البلدية:</label>
                <!-- التعديل: region مطابق للموديل -->
                <input type="text" name="region" class="form-control" placeholder="حدد مكان رصد الإصابة" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>نوع الحيوان:</label>
                    <select name="type_animal" class="form-control">
                        <option value="أبقار">أبقار</option>
                        <option value="أغنام">أغنام</option>
                        <option value="ماعز">ماعز</option>
                        <option value="دواجن">دواجن</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>عدد الحالات:</label>
                    <!-- التعديل: nombre_cas مطابق للموديل -->
                    <input type="number" name="nombre_cas" class="form-control" placeholder="0" min="1">
                </div>
            </div>

            <div class="form-group">
                <label>تاريخ اكتشاف الإصابة:</label>
                <!-- إضافة حقل التاريخ المطلوب في الـ Migration -->
                <input type="date" name="date_decouverte" class="form-control" required>
            </div>

            <div class="form-group">
                <label>وصف الأعراض الملاحظة:</label>
                <!-- التعديل: description مطابق للموديل -->
                <textarea name="description" class="form-control" rows="4" placeholder="اكتب تفاصيل الحالة الميدانية..." required></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> إرسال التقرير الآن
            </button>

            <a href="{{ route('veterinaire.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-right"></i> العودة للوحة التحكم
            </a>
        </form>
    </div>

</body>
</html>