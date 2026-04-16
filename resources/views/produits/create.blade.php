<h1>Ajouter Produit</h1>

<form method="POST" action="/produits">
    @csrf

    <input type="text" name="nom" placeholder="Nom">
    <input type="number" name="quantite" placeholder="Quantité">

    <button type="submit">Ajouter</button>
</form>