<!DOCTYPE html>
<html>
<head>
    <title>Gestion Store</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: linear-gradient(135deg, #e0f2fe, #f0fdf4);
    min-height: 100vh;
}

/* TITLE */
h2 {
    text-align: center;
    margin: 25px 0;
    color: #064e3b;
    font-size: 28px;
}

/* FORM */
form {
    text-align: center;
    margin: 15px auto;
}

input {
    padding: 12px;
    margin: 6px;
    border-radius: 10px;
    border: 1px solid #ddd;
    width: 220px;
    transition: 0.3s;
}

input:focus {
    outline: none;
    border-color: #22c55e;
    box-shadow: 0 0 8px rgba(34,197,94,0.3);
}

/* BUTTONS */
button {
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.add {
    background: linear-gradient(90deg, #22c55e, #16a34a);
    color: white;
}
.add:hover {
    transform: scale(1.05);
}

.delete {
    background: #ef4444;
    color: white;
}
.delete:hover {
    background: #dc2626;
}

.edit {
    background: #f59e0b;
    color: white;
}
.edit:hover {
    background: #d97706;
}

.view {
    background: #3b82f6;
    color: white;
}
.view:hover {
    background: #2563eb;
}

/* TABLE */
table {
    width: 90%;
    margin: 30px auto;
    border-collapse: collapse;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

th {
    background: linear-gradient(90deg, #16a34a, #22c55e);
    color: white;
    padding: 15px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

tr:hover {
    background: #f0fdf4;
    transition: 0.3s;
}

/* SEARCH RESULTS */
#results {
    width: 220px;
    margin: auto;
    border-radius: 10px;
    overflow: hidden;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

#results div {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

#results div:hover {
    background: #dcfce7;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
}

.modal-content {
    background: white;
    width: 320px;
    margin: 15% auto;
    padding: 25px;
    border-radius: 15px;
    text-align: center;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
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

    <!-- 🔥 Live Search -->
    <input type="text" id="produitInput" placeholder="اكتب اسم المنتج">

    <div id="results"></div>

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
        <a href="{{ route('produits.edit', $p->id) }}">
            <button class="edit">Modifier</button>
        </a>

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

// 🔥 LIVE SEARCH
document.getElementById('produitInput').addEventListener('keyup', function () {
    let value = this.value.toLowerCase();
    let resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = '';

    if (value.length < 2) return;

    let produits = @json($allProduits);

    let filtered = produits.filter(p =>
        p.nom.toLowerCase().includes(value)
    );

    filtered.forEach(p => {
        let div = document.createElement('div');
        div.innerHTML = p.nom;
        div.style.padding = '5px';
        div.style.cursor = 'pointer';

        div.onclick = function () {
            document.getElementById('produitInput').value = p.nom;
            document.getElementById('produit_id').value = p.id;
            resultsDiv.innerHTML = '';
        };

        resultsDiv.appendChild(div);
    });
});
</script>

</body>
</html>