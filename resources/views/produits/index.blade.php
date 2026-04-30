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

input {
    padding: 10px;
    margin: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

/* BUTTONS */
button {
    padding: 9px 18px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
}

.add { background: #22c55e; color:white; }
.delete { background: #ef4444; color:white; }
.edit { background: #f59e0b; color:white; }
.view { background: #3b82f6; color:white; }

/* MODAL */
.modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
}

.modal-content {
    background: white;
    width: 300px;
    margin: 15% auto;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}
    </style>
</head>

<body>

<h2 style="text-align:center;">Gestion Store</h2>

<!-- 🔍 RECHERCHE -->
<form method="GET" action="{{ route('produits.index') }}">
    <input type="text" name="search" placeholder="Rechercher produit...">
    <button type="submit" class="add">بحث</button>
</form>

<!-- ➕ AJOUT -->
@can('create', App\Models\Store::class)
<form method="POST" action="{{ route('produits.store') }}">
    @csrf

    <h3 style="color:#14532d;">إضافة منتج</h3>

    <!-- autocomplete -->
    <input list="produitsList" id="produitInput" placeholder="اكتب اسم المنتج">

    <select name="produit_id" required>
    <option value="">-- اختر المنتج --</option>
    @foreach($allProduits as $prod)
        <option value="{{ $prod->id }}">
            {{ $prod->nom }}
        </option>
    @endforeach
</select>

    <!-- important -->
    <input type="hidden" name="produit_id" id="produit_id" required>

    <input type="number" name="quantite" placeholder="Quantité" required>
    <input type="number" name="prix" placeholder="Prix" required>
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
    <td>{{ $p->produit->nom }}</td>

    <td>
        <button class="view" onclick="toggleDetails({{ $p->id }})">View</button>

        <div id="details-{{ $p->id }}" style="display:none;">
            <p>Quantité: {{ $p->quantite }}</p>
            <p>Prix: {{ $p->prix }}</p>
        </div>
    </td>

    <td>
        <!-- edit -->
        <a href="{{ route('produits.edit', $p->id) }}">
            <button class="edit">Modifier</button>
        </a>

        <!-- delete -->
        <form method="POST"
              action="{{ route('produits.destroy', $p->id) }}"
              style="display:inline;">
            @csrf
            @method('DELETE')

            <button type="button" class="delete"
                onclick="openModal(this.closest('form'))">
                Supprimer
            </button>
        </form>
    </td>
</tr>
@endforeach

</table>

<!-- MODAL -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <p>هل أنت متأكد؟</p>
        <button onclick="confirmDelete()" class="delete">نعم</button>
        <button onclick="closeModal()" class="edit">لا</button>
    </div>
</div>

<script>
// modal
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

// details
function toggleDetails(id) {
    let div = document.getElementById('details-' + id);
    div.style.display = (div.style.display === 'none') ? 'block' : 'none';
}

// 🔥 autocomplete FIX
document.getElementById('produitInput').addEventListener('change', function () {
    let input = this.value;
    let options = document.querySelectorAll('#produitsList option');
    let hiddenInput = document.getElementById('produit_id');

    hiddenInput.value = '';

    options.forEach(option => {
        if (option.value.trim().toLowerCase() === input.trim().toLowerCase()) {
            hiddenInput.value = option.dataset.id;
        }
    });

    if (hiddenInput.value === '') {
        alert('⚠️ اختار منتج من القائمة');
        this.value = '';
    }
});
</script>

</body>
</html>