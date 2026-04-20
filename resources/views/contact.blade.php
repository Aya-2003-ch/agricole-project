<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Contact - AgroDz</title>

<style>
body {
    font-family: Arial;
    margin: 0;
    background: #f4f7f5;
}

/* HEADER */
.header {
    background: linear-gradient(90deg, #27ae60, #1e7d4f);
    color: white;
    padding: 50px;
    text-align: center;
}

.header h1 {
    margin-bottom: 10px;
}

/* CONTAINER */
.container {
    max-width: 800px;
    margin: auto;
    padding: 40px;
}

/* CARD */
.card {
    background: white;
    padding: 25px;
    margin: 20px 0;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.card h3 {
    color: #27ae60;
    margin-bottom: 10px;
}

/* INPUT */
textarea {
    width: 100%;
    height: 120px;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #ccc;
    resize: none;
    outline: none;
    font-size: 14px;
}

button {
    margin-top: 10px;
    padding: 10px 20px;
    border: none;
    background: #27ae60;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
}

button:hover {
    background: #1e7d4f;
}

/* FOOTER */
footer {
    text-align: center;
    padding: 20px;
    background: #1e2a2f;
    color: white;
}
</style>
</head>

<body>

<div class="header">
    <h1>📞 Contactez-nous</h1>
    <p>لمزيد من المعلومات والاستفسارات تواصلوا معنا عبر</p>
</div>

<div class="container">

    <div class="card">
        <h3>📱 رقم الهاتف</h3>
        <p>+213 660895634  </p>
    </div>

    <div class="card">
        <h3>📘 Facebook</h3>
        <p><a href="https://facebook.com/agrodz" target="_blank">facebook.com/AgroDz</a></p>
    </div>

    <div class="card">
        <h3>💬او اترك استفسارك هنا </h3>
        <p>سيتم الرد عليك في أقرب وقت ممكن</p>

        <textarea placeholder="اكتب رسالتك أو استفسارك هنا..."></textarea>

        <button>إرسال</button>
    </div>

</div>

<footer>
    © 2026 AgroDz - Contact
</footer>

</body>
</html>