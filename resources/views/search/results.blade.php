<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>نتائج البحث</title>

<style>
body {
    font-family: Arial;
    background: #f4f7f6;
    margin: 0;
    padding: 0;
}

/* container */
.container {
    width: 80%;
    margin: 30px auto;
}

/* card */
.card {
    background: white;
    padding: 20px;
    margin-bottom: 15px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

/* title */
.card h3 {
    color: #14532d;
    margin-bottom: 10px;
}

/* text */
.card p {
    margin: 5px 0;
    color: #444;
}

/* button */
button {
    margin-top: 10px;
    padding: 8px 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    background: #16a34a;
    color: white;
    font-weight: bold;
}

button:hover {
    background: #15803d;
}

/* back */
.back {
    display: inline-block;
    margin-bottom: 20px;
    text-decoration: none;
    color: #16a34a;
    font-weight: bold;
}
</style>

</head>

<body>

<div class="container">

    <a href="{{ url()->previous() }}" class="back">⬅ رجوع</a>

    <h2>🔍 نتائج البحث</h2>

    @if($results->count() > 0)

        @foreach($results as $item)

            <div class="card">

                <h3>💊 {{ $item->produit->nom }}</h3>

                <p>👨‍🌾 الموزع: {{ $item->distributeur->nom }}</p>

                <p>📍 العنوان: {{ $item->distributeur->address }}</p>

                <p>💰 السعر: {{ $item->prix }} دج</p>

                <p>📦 الكمية: {{ $item->quantite }}</p>

                <!-- 🔥 زر التفاصيل -->
                <a href="{{ route('produits.show', $item->id) }}">
                    <button>📄 تفاصيل</button>
                </a>

                <!-- 📞 اتصال -->
                <a href="tel:{{ $item->distributeur->telephone }}">
                    <button style="background:#3b82f6;">📞 اتصال</button>
                </a>

            </div>

        @endforeach

    @else
        <p>❌ لا توجد نتائج لهذا الدواء</p>
    @endif

</div>

</body>
</html>