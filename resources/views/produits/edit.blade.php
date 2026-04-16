<!DOCTYPE html>
<html>
<head>
    <title>Modifier Produit</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
        }

        .container {
            width: 40%;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: orange;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: darkorange;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Modifier Produit</h2>

    <form method="POST" action="{{ route('produit_agri.update', $produit->id) }}">
        @csrf
        @method('PUT')

        <input type="number" name="quantite" value="{{ $produit->quantite }}">
        <input type="number" name="prix" value="{{ $produit->prix }}">
        <input type="date" name="date_exp" value="{{ $produit->date_exp }}">

        <button>Enregistrer</button>
    </form>

    <a href="{{ route('produit_agri.index') }}" class="back">⬅ Retour</a>
</div>

</body>
</html>