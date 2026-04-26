<!DOCTYPE html>
<html>
<head>
    <title>Gestion Store</title>

    <style>
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: linear-gradient(135deg, #ecfdf5, #f0fdf4);
}

/* TITLE */
h2 {
    margin-top: 20px;
    color: #14532d;
}

/* TABLE */
table {
    width: 85%;
    margin: 30px auto;
    border-collapse: collapse;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

th, td {
    padding: 14px;
    text-align: center;
}

th {
    background: linear-gradient(90deg, #27894e, #16a34a);
    color: white;
    font-weight: bold;
}

td {
    border-bottom: 1px solid #eee;
}

tr:hover {
    background: #f0fdf4;
}

/* FORM */
form {
    text-align: center;
    margin-top: 20px;
}


input, select {
    padding: 10px;
    margin: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
    outline: none;
    transition: 0.3s;
}

input:focus, select:focus {
    border-color: #16a34a;
    box-shadow: 0 0 5px rgba(65, 165, 102, 0.3);
}

/* BUTTONS */
button {
    padding: 9px 18px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

/* ADD */
.add {
    background: linear-gradient(90deg, #29ad5a, #22c55e);
}

.add:hover {
    transform: scale(1.05);
}

/* DELETE */
.delete {
    background: linear-gradient(90deg, #dc2626, #ef4444);
}

.delete:hover {
    transform: scale(1.05);
}

/* EDIT */
.edit {
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
}

.edit:hover {
    transform: scale(1.05);
}

/* VIEW */
.view {
    background: linear-gradient(90deg, #2563eb, #3b82f6);
}

.view:hover {
    transform: scale(1.05);
}

/* DETAILS */
#details- {
    transition: 0.3s;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
}

.modal-content {
    background: white;
    width: 320px;
    margin: 15% auto;
    padding: 25px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-content p {
    margin-bottom: 20px;
    font-weight: bold;
    color: #333;
}
    </style>
</head>

<body>

<h2 style="text-align:center;">Gestion Store</h2>

<!-- RECHERCHE -->
<form method="GET" action="{{ route('produits.index') }}">
    <input type="text" name="search" placeholder="Rechercher produit...">
    <button type="submit" class="add">بحث</button>
</form>

<!-- AJOUT (Policy: create) -->
@can('create', App\Models\Store::class)
<form method="POST" action="{{ route('produits.store') }}">
    @csrf
   <h3 style="margin-bottom:15px; color:#14532d;">
        ➕ إضافة منتج جديد
    </h3>
    <input list="produitsList" id="produitInput" placeholder="اكتب اسم المنتج">

<datalist id="produitsList">
    @foreach($allProduits as $prod)
        <option value="{{ $prod->nom }}" data-id="{{ $prod->id }}"></option>
    @endforeach
</datalist>

<input type="hidden" name="produit_id" id="produit_id">

    <input type="number" name="quantite" placeholder="Quantité">
    <input type="number" name="prix" placeholder="Prix">
    <input type="date" name="date_exp">

    <button class="add">اضافة</button>
</form>
@endcan

<!-- 📊 TABLE -->
<table>
    <tr>
        <th>Nom</th>
        <th>Détails</th>
        <th>Actions</th>
    </tr>

    @foreach($produits as $p)
    <tr>
        <!-- Nom -->
        <td>{{ $p->produit->nom }}</td>

        <!-- View (Policy: view) -->
        <td>
            @can('view', $p)
                <button class="view" onclick="toggleDetails({{ $p->id }})">
                    View
                </button>

                <div id="details-{{ $p->id }}" style="display:none; margin-top:10px;">
                    <p><strong>Quantité:</strong> {{ $p->quantite }}</p>
                    <p><strong>Prix:</strong> {{ $p->prix }}</p>
                </div>
            @else
                ❌ Non autorisé
            @endcan
        </td>

        <!-- Actions -->
        <td>

            <!-- Modifier -->
            @can('update', $p)
                <a href="{{ route('produit_agri.edit', $p->id) }}">
                    <button class="edit">Modifier</button>
                </a>
            @endcan

            /* Supprimer*/ 
            @can('delete', $p)
                <form method="POST"
                      action="{{ route('produit_agri.destroy', $p->id) }}"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="delete"
                        onclick="openModal(this.closest('form'))">
                        Supprimer
                    </button>
                </form>
            @endcan

        </td>
    </tr>
    @endforeach

</table>

// Modal 
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <p>هل أنت متأكد من الحذف؟</p>
        <button onclick="confirmDelete()" class="delete">نعم</button>
        <button onclick="closeModal()" class="edit">إلغاء</button>
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

    if (div.style.display === 'none') {
        div.style.display = 'block';
    } else {
        div.style.display = 'none';
    }
}
// autocomplete produit
document.getElementById('produitInput').addEventListener('input', function () {
    let input = this.value;
    let options = document.querySelectorAll('#produitsList option');
    let hiddenInput = document.getElementById('produit_id');

    hiddenInput.value = '';

    options.forEach(option => {
        if (option.value === input) {
            hiddenInput.value = option.dataset.id;
        }
    });
});
</script>

</body>
</html>