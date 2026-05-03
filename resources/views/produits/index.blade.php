<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>AgroDz - Gestion Store</title>
    <style>
        
        :root {
            --primary-dark: #2d4a36;    
            --accent-green: #5a8d5a;    
            --light-green: #f0fdf4;     
            --bg-body: #f8fafc;         
            --white: #ffffff;
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            --radius: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* رأس الصفحة */
        .welcome-card {
            background: var(--white);
            padding: 30px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border-right: 8px solid var(--accent-green);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* زر العودة للـ Dashboard */
        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: #f1f5f9; 
            color: var(--primary-dark);
            border-radius: 12px;
            text-decoration: none;
            transition: 0.3s;
            border: 1px solid #e2e8f0;
        }

        .btn-back:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateX(5px); 
        }

        .welcome-card h2 {
            color: var(--primary-dark);
            font-size: 26px;
        }

        /* تنسيق النماذج */
        .section-card {
            background: var(--white);
            padding: 25px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .section-title {
            color: var(--accent-green);
            margin-bottom: 20px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }

        input {
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            outline: none;
            transition: 0.3s;
            flex: 1;
            min-width: 180px;
        }

        input:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(90, 141, 90, 0.1);
        }

        /* الأزرار */
        button {
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add {
            background: var(--primary-dark);
            color: white;
        }

        .btn-add:hover {
            background: var(--accent-green);
            transform: translateY(-2px);
        }

        .btn-view { background: #eff6ff; color: #2563eb; }
        .btn-edit { background: #fffbeb; color: #d97706; }
        .btn-delete { background: #fef2f2; color: #dc2626; }

        /* الجدول */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        th {
            padding: 10px 20px;
            color: var(--text-gray);
            text-align: right;
            font-weight: 500;
        }

        td {
            background: var(--white);
            padding: 20px;
            border-top: 1px solid #f3f4f6;
            border-bottom: 1px solid #f3f4f6;
        }

        td:first-child {
            border-right: 1px solid #f3f4f6;
            border-top-right-radius: var(--radius);
            border-bottom-right-radius: var(--radius);
        }

        td:last-child {
            border-left: 1px solid #f3f4f6;
            border-top-left-radius: var(--radius);
            border-bottom-left-radius: var(--radius);
        }

        tr:hover td {
            background: var(--light-green);
        }

        /* البحث  */
        #results {
            position: absolute;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 250px;
            margin-top: 50px;
            z-index: 10;
            overflow: hidden;
        }

        #results div {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
        }

        #results div:hover {
            background: var(--light-green);
            color: var(--primary-dark);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(4px);
            z-index: 100;
        }

        .modal-content {
            background: white;
            width: 350px;
            margin: 15% auto;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .modal-btns {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .hidden-details {
            background: #f9fafb;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    
    <!-- HEADER -->
    <div class="welcome-card">
        <div class="header-content">
            <!-- زر العودة لداشبورد الموزع -->
            <a href="{{ route('distributeur.dashboard') }}" class="btn-back" title="العودة للوحة تحكم الموزع">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <div>
                <h2>إدارة المتجر - AgroDz</h2>
                <p style="color: var(--text-gray); margin-top: 5px;">تعديل مخزون الموزع وتتبع المنتجات.</p>
            </div>
        </div>
        
        <form method="GET" action="{{ route('produits.index') }}" style="margin: 0;">
            <input type="text" name="search" placeholder="بحث سريع عن منتج...">
            <button type="submit" class="btn-add">بحث</button>
        </form>
    </div>

    <!-- ADD FORM -->
    @can('create', App\Models\Store::class)
    <div class="section-card">
        <h3 class="section-title">📦 إضافة منتج جديد للمخزن</h3>
        <form method="POST" action="{{ route('produits.store') }}">
            @csrf
            <div style="position: relative; flex: 1;">
                <input type="text" id="produitInput" placeholder="اسم المنتج (ابحث هنا...)" autocomplete="off">
                <div id="results"></div>
            </div>
            
            <input type="hidden" name="produit_id" id="produit_id" required>
            <input type="number" name="quantite" placeholder="الكمية" required>
            <input type="number" name="prix" placeholder="السعر" required>
            <input type="date" name="date_exp">

            <button type="submit" class="btn-add">إضافة للمخزن</button>
        </form>
    </div>
    @endcan

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>المنتج</th>
                <th>التفاصيل</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $p)
            <tr>
                <td style="font-weight: 600; color: var(--primary-dark);">{{ $p->produit->nom }}</td>
                <td>
                    <button class="btn-view" onclick="toggleDetails({{ $p->id }})">عرض التفاصيل</button>
                    <div id="details-{{ $p->id }}" class="hidden-details" style="display:none;">
                        <p>الكمية المتوفرة: <strong>{{ $p->quantite }}</strong></p>
                        <p>سعر الوحدة: <strong>{{ $p->prix }} دج</strong></p>
                    </div>
                </td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('produits.edit', $p->id) }}" style="text-decoration: none;">
                            <button class="btn-edit">تعديل</button>
                        </a>
                        <form method="POST" action="{{ route('produits.destroy', $p->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-delete" onclick="openModal(this.closest('form'))">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <h3 style="color: var(--primary-dark);">تأكيد الحذف</h3>
        <p style="margin: 15px 0; color: var(--text-gray);">هل أنت متأكد من حذف هذا المنتج؟</p>
        <div class="modal-btns">
            <button onclick="confirmDelete()" class="btn-add" style="background: #dc2626;">نعم، احذف</button>
            <button onclick="closeModal()" class="btn-edit" style="background: #e5e7eb; color: #374151;">إلغاء</button>
        </div>
    </div>
</div>

<script>
    let deleteForm;

    function openModal(form) {
        deleteForm = form;
        document.getElementById('confirmModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    function confirmDelete() {
        deleteForm.submit();
    }

    function toggleDetails(id) {
        let div = document.getElementById('details-' + id);
        div.style.display = (div.style.display === 'none') ? 'block' : 'none';
    }

    // LIVE SEARCH
    document.getElementById('produitInput').addEventListener('keyup', function () {
        let value = this.value.toLowerCase();
        let resultsDiv = document.getElementById('results');
        resultsDiv.innerHTML = '';

        if (value.length < 2) return;

        let produits = @json($allProduits);

        let filtered = produits.filter(p => p.nom.toLowerCase().includes(value));

        filtered.forEach(p => {
            let div = document.createElement('div');
            div.innerHTML = p.nom;
            div.onclick = function () {
                document.getElementById('produitInput').value = p.nom;
                document.getElementById('produit_id').value = p.id;
                resultsDiv.innerHTML = '';
            };
            resultsDiv.appendChild(div);
        });
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById('confirmModal')) {
            closeModal();
        }
    }
</script>

</body>
</html>