<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>AgroDz</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#f5f7f6;
}

/* CONTAINER */
.container{
    width:90%;
    margin:auto;
}

/* NAVBAR */
nav{
    position:sticky;
    top:0;
    background:white;
    padding:15px 0;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    z-index:100;
}

.nav-content{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logo{
    color:#16a34a;
    font-weight:bold;
    font-size:22px;
}

.nav-icons a{
    margin-left:15px;
    color:#333;
    font-size:18px;
}

/* HERO */
.hero{
    margin-top:20px;
    background:white;
    border-radius:20px;
    padding:40px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.hero-text{
    width:50%;
}

.hero-text h1{
    font-size:38px;
    color:#14532d;
}

.hero-text p{
    margin:15px 0;
    color:#555;
    line-height:1.6;
}

.hero img{
    width:420px;
    border-radius:15px;
}

/* BUTTONS */
.buttons{
    margin-top:15px;
}

.btn{
    padding:10px 20px;
    border-radius:25px;
    text-decoration:none;
    margin-right:10px;
    display:inline-block;
}

.btn-main{
    background:#16a34a;
    color:white;
}

.btn-outline{
    border:1px solid #16a34a;
    color:#16a34a;
}

/* SEARCH */
.search-box{
    margin:30px auto;
    width:60%;
    background:white;
    border-radius:40px;
    display:flex;
    padding:8px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.search-box input{
    flex:1;
    border:none;
    outline:none;
    padding:10px;
}

.search-box button{
    background:#16a34a;
    color:white;
    border:none;
    padding:10px 25px;
    border-radius:30px;
}

/* SECTION */
.section{
    margin-top:40px;
    text-align:center;
}

.section h2{
    color:#16a34a;
    margin-bottom:30px;
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
}

.card{
    background:white;
    padding:25px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card i{
    font-size:30px;
    color:#16a34a;
    margin-bottom:10px;
}

.card h3{
    margin-bottom:10px;
}

.card p{
    color:#666;
}

/* FOOTER */
footer{
    margin-top:50px;
    background:#0f172a;
    color:white;
    text-align:center;
    padding:15px;
}

/* RESPONSIVE */
@media(max-width:768px){
    .hero{
        flex-direction:column;
        text-align:center;
    }

    .hero-text{
        width:100%;
    }

    .hero img{
        width:300px;
        margin-top:20px;
    }

    .search-box{
        width:90%;
    }
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav>
    <div class="container nav-content">
        <div class="logo">🌿 AgroDz</div>

        <div class="nav-icons">
            <a href="{{ route('login') }}"><i class="fas fa-user"></i></a>
            <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i></a>
        </div>
    </div>
</nav>

<!-- HERO -->
<div class="container">
    <div class="hero">

        <div class="hero-text">
            <h1>كل ما تحتاجه لمزرعتك 🌿</h1>
            <p>
                منصة ذكية تساعدك في البحث عن الأدوية، 
                التواصل مع الموزعين، ومتابعة صحة حيواناتك بسهولة.
            </p>

            <div class="buttons">
                <a href="{{ route('login') }}" class="btn btn-main">🔍 ابدأ الآن</a>
                <a href="{{ route('register') }}" class="btn btn-outline">إنشاء حساب</a>
            </div>
        </div>

        <img src="{{ asset('images/hero.png') }}">

    </div>
</div>

<!-- SEARCH -->
<div class="search-box">
    <input type="text" placeholder="🔍 ابحث عن دواء...">
    <button>بحث</button>
</div>

<!-- SERVICES -->
<div class="container section">
    <h2>خدماتنا</h2>

    <div class="cards">

        <div class="card">
            <i class="fas fa-pills"></i>
            <h3>الأدوية</h3>
            <p>ابحث عن الأدوية البيطرية بسهولة</p>
        </div>

        <div class="card">
            <i class="fas fa-truck"></i>
            <h3>الموزعين</h3>
            <p>أقرب موزع في منطقتك</p>
        </div>

        <div class="card">
            <i class="fas fa-cow"></i>
            <h3>الحيوانات</h3>
            <p>متابعة صحة الحيوانات</p>
        </div>

        <div class="card">
            <i class="fas fa-chart-line"></i>
            <h3>إحصائيات</h3>
            <p>تحليل الأداء</p>
        </div>

    </div>
</div>

<footer>
    © 2026 AgroDz
</footer>

</body>
</html>