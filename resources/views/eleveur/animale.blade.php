<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - إدارة قطيع الحيوانات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2d6a4f; 
            --secondary: #1b4332;
            --bg: #f8f9fa;
            --white: #ffffff;
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
        }

        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg); display: flex; }

        /* Sidebar */
        .sidebar { 
            width: 260px; height: 100vh; background: var(--secondary); color: white; position: fixed; right: 0; z-index: 1000; display: flex; flex-direction: column; 
        }
        .sidebar h2 { text-align: center; padding: 20px; border-bottom: 1px solid #2d6a4f; margin: 0; }
        .sidebar a { display: block; padding: 15px 25px; color: #d1d1d1; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: var(--primary); color: white; }

        .logout-section { margin-top: auto; border-top: 1px solid #2d6a4f; }
        .btn-logout {
            width: 100%; background: none; border: none; color: #ffbaba; padding: 15px 25px; text-align: right; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: 0.3s; font-family: inherit;
        }
        .btn-logout:hover { background: var(--danger); color: white; }

        /* Main Content */
        .content { margin-right: 260px; width: calc(100% - 260px); padding: 30px; }

        .header { background: var(--white); padding: 20px; border-radius: 15px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }

        .btn-add {
            background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.3s;
        }
        .btn-add:hover { background: var(--secondary); }

        /* Table Styles */
        .table-card { background: var(--white); padding: 20px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; text-align: right; margin-top: 15px; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: var(--secondary); font-weight: bold; }
        
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; }
        .badge-info { background: #e0f2fe; color: #0369a1; }

        .btn-action { border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: bold; transition: 0.2s; margin-left: 5px; }
        .btn-edit { background: #fef3c7; color: #d97706; }
        .btn-edit:hover { background: #fde68a; }
        .btn-delete { background: #fee2e2; color: #dc2626; }
        .btn-delete:hover { background: #fecaca; }

        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; }
        .modal-content { background: white; padding: 25px; border-radius: 15px; width: 450px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: bold; color: #333; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; box-sizing: border-box; }

        /* مخصص للـ Delete Modal */
        .delete-modal-content { text-align: center; width: 380px; padding: 30px 20px; }
        .delete-icon { font-size: 50px; color: var(--danger); margin-bottom: 15px; }
        
        .alert-success { background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>AgroDz 🚜</h2>
        <a href="{{ route('eleveur.dashboard') }}"><i class="fas fa-home"></i> الرئيسية</a>
        <a href="#" class="active"><i class="fas fa-paw"></i> إدارة قطيعي</a>
        <a href="{{ route('eleveur.isticharati') }}"><i class="fas fa-file-medical"></i> استشاراتي</a>
        <a href="{{ route('eleveur.chats') }}"><i class="fas fa-comments"></i> المحادثات</a>
        
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </div>

    <div class="content">
        <div class="header">
            <h3 style="margin:0; color: var(--secondary);"><i class="fas fa-list"></i> سجل الحيوانات والقطيع</h3>
            <button class="btn-add" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة حيوان جديد</button>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>النوع (Espèce)</th>
                        <th>رمز التعريف (Code)</th>
                        <th>العمر / تاريخ الميلاد</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($animals as $animal)
                        <tr>
                            <td><span class="badge badge-info">{{ $animal->type }}</span></td>
                            <td><strong>{{ $animal->identification_code ?? 'غير مسجل' }}</strong></td>
                            <td>{{ $animal->age ?? 'غير محدد' }}</td>
                            <td>
                                <button class="btn-action btn-edit" 
                                        onclick="openEditModal('{{ $animal->id }}', '{{ $animal->type }}', '{{ $animal->identification_code }}', '{{ $animal->age }}')">
                                    <i class="fas fa-edit"></i> تعديل
                                </button>
                                
                                <button type="button" class="btn-action btn-delete" onclick="openDeleteModal('{{ $animal->id }}')">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #999; padding: 40px;">
                                <i class="fas fa-paw" style="font-size: 40px; display:block; margin-bottom:10px;"></i>
                                لا توجد حيوانات مسجلة في قطيعك حالياً. اضغط على زر الإضافة للبدء.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <h3 style="margin-top:0; color: var(--primary);"><i class="fas fa-plus-circle"></i> إضافة حيوان للقطيع</h3>
            <form action="{{ route('eleveur.animals.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>نوع الحيوان:</label>
                    <select name="type" required>
                        <option value="بقرة">بقرة (Vache)</option>
                        <option value="خروف">خروف (Mouton)</option>
                        <option value="ماعز">ماعز (Chèvre)</option>
                        <option value="حصان">حصان (Cheval)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>رمز التعريف الإلكتروني أو السجل (اختياري):</label>
                    <input type="text" name="identification_code" placeholder="مثال: DZ-2026-89">
                </div>
                <div class="form-group">
                    <label>العمر أو تاريخ الميلاد:</label>
                    <input type="text" name="age" required placeholder="مثال: سنتين، أو 14 شهر">
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn-add" style="width:50%; justify-content:center;">حفظ الحيوان</button>
                    <button type="button" onclick="closeModal('addModal')" class="btn-add" style="width:50%; background:#ccc; color:#333; justify-content:center;">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3 style="margin-top:0; color: var(--warning);"><i class="fas fa-edit"></i> تعديل بيانات الحيوان</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>نوع الحيوان:</label>
                    <select name="type" id="edit_type" required>
                        <option value="بقرة">بقرة (Vache)</option>
                        <option value="خروف">خروف (Mouton)</option>
                        <option value="ماعز">ماعز (Chèvre)</option>
                        <option value="حصان">حصان (Cheval)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>رمز التعريف الإلكتروني:</label>
                    <input type="text" name="identification_code" id="edit_code">
                </div>
                <div class="form-group">
                    <label>العمر:</label>
                    <input type="text" name="age" id="edit_age" required>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn-add" style="width:50%; background: var(--warning); justify-content:center;">تحديث البيانات</button>
                    <button type="button" onclick="closeModal('editModal')" class="btn-add" style="width:50%; background:#ccc; color:#333; justify-content:center;">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content delete-modal-content">
            <div class="delete-icon"><i class="fas fa-exclamation-circle"></i></div>
            <h3 style="margin: 0 0 10px 0; color: #333;">هل أنتِ متأكدة؟</h3>
            <p style="color: #666; margin: 0 0 25px 0; font-size: 15px;">سيتم حذف هذا الحيوان من السجل نهائياً ولا يمكن التراجع عن هذا الإجراء.</p>
            
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button type="submit" class="btn-action btn-delete" style="padding: 10px 25px; font-size: 14px; margin: 0;">نعم، إحذف</button>
                    <button type="button" onclick="closeModal('deleteModal')" class="btn-action" style="background: #e2e8f0; color: #334155; padding: 10px 25px; font-size: 14px; margin: 0;">لا، إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
        }

        function openEditModal(id, type, code, age) {
            document.getElementById('editForm').action = `/eleveur/animale/${id}`;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_age').value = age;

            document.getElementById('editModal').style.display = 'flex';
        }

        // 👈 دالة فتح نافذة الحذف المخصصة وتمرير الـ ID ديناميكياً لـ Form الإرسال
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = `/eleveur/animale/${id}`;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>