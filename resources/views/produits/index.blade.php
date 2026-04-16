<!DOCTYPE html>
<html>
<head>
    <title>Gestion Store</title>

    <style>
        body { font-family: Arial; background: #f5f5f5; }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background: #2c3e50;
            color: white;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        input, select {
            padding: 8px;
            margin: 5px;
        }

        button {
            padding: 8px 15px;
            color: white;
            border: none;
        }

        .add { background: green; }
        .delete { background: red; }
        .edit { background: orange; }
        .view { background: blue; }

        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: white;
            width: 30%;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

<h2 style="text-align:center;">Gestion Store</h2>

<!-- RECHERCHE -->
<form method="GET" action="{{ route('produit_agri.index') }}">
    <input type="text" name="search" placeholder="Rechercher produit...">
    <button type="submit" class="add">Rechercher</button>
</form>

<!-- AJOUT (Policy: create) -->
@can('create', App\Models\Store::class)
<form method="POST" action="{{ route('produit_agri.store') }}">
    @csrf

    <select name="produit_id">
        @foreach($allProduits as $prod)
            <option value="{{ $prod->id }}">
                {{ $prod->nom }}
            </option>
        @endforeach
    </select>

    <input type="number" name="quantite" placeholder="Quantité">
    <input type="number" name="prix" placeholder="Prix">
    <input type="date" name="date_exp">

    <button class="add">Ajouter</button>
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
</script>

</body>
</html>