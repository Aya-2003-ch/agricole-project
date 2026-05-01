<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>تفاصيل الدواء</title>

<style>
body {
    font-family: Arial;
    background: #f4f7f6;
    margin: 0;
    padding: 0;
}

.container {
    width: 50%;
    margin: 50px auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

/* titles */
h2 {
    color: #14532d;
    margin-bottom: 15px;
}

h3 {
    color: #16a34a;
    margin-top: 20px;
}

/* text */
p {
    font-size: 16px;
    margin: 8px 0;
}

/* line */
hr {
    margin: 20px 0;
}

/* button */
button {
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    background: #16a34a;
    color: white;
    font-weight: bold;
    margin-top: 10px;
}

button:hover {
    background: #15803d;
}

/* call button */
.call {
    background: #3b82f6;
}

.call:hover {
    background: #2563eb;
}

/* back */
.back {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    color: #16a34a;
    font-weight: bold;
}
</style>

</head>

<body>

<div class="container">

    <!-- 💊 PRODUCT INFO -->
    <h2>💊 {{ $store->produit->nom }}</h2>

    <p>💰 السعر: {{ $store->prix }} دج</p>
    <p>📦 الكمية: {{ $store->quantite }}</p>

    <hr>

    <!-- 👨‍🌾 DISTRIBUTEUR -->
    <h3>👨‍🌾 الموزع</h3>

    <p>👤 الاسم: {{ $store->distributeur->nom }}</p>
    <p>📍 العنوان: {{ $store->distributeur->address }}</p>
    <p>📞 الهاتف: {{ $store->distributeur->telephone }}</p>

    <!-- 📞 CALL BUTTON -->
    <a href="tel:{{ $store->distributeur->telephone }}">
        <button class="call">📞 اتصال بالموزع</button>
    </a>

    <!-- 🔙 BACK -->
    <br>
    <a href="{{ url()->previous() }}" class="back">⬅ رجوع</a>

</div>

</body>
</html>