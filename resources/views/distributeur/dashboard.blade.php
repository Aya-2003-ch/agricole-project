<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الموزع | AgroDz</title>
    
    <!-- الروابط الأساسية: أيقونات وخطوط -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* الألوان مستوحاة من صورة المنصة image_19e1bd.jpg */
            --primary-dark: #344e41; 
            --primary-green: #588157;
            --accent-green: #a3b18a;
            --light-bg: #f4f7f6;
            --white: #ffffff;
            --text-main: #2d3436;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--text-main);
            display: flex;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-dark), #1b2e25);
            position: fixed;
            right: 0;
            color: white;
            padding: 30px 15px;
            box-shadow: -5px 0 15px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .sidebar-brand {
            font-size: 26px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 20px;
        }

        .sidebar-brand i {
            color: var(--accent-green);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            margin-bottom: 10px;
            color: #dad7cd;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .sidebar a:hover, .sidebar a.active {
            background: var(--primary-green);
            color: white;
            transform: translateX(-5px);
        }

        .logout-btn {
            margin-top: 50px;
            background: rgba(231, 76, 60, 0.1) !important;
            color: #e74c3c !important;
        }

        .logout-btn:hover {
            background: #e74c3c !important;
            color: white !important;
        }

        /* --- CONTENT AREA --- */
        .content {
            margin-right: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 40px;
        }

        /* --- HEADER & WELCOME MESSAGE --- */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--white);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            border-right: 6px solid var(--primary-green);
        }

        .welcome-text {
            font-size: 24px;
            color: var(--primary-dark);
            font-weight: 700;
        }

        .user-name {
            color: var(--primary-green);
        }

        .badge {
            background: #dcfce7;
            color: #14532d;
            font-size: 12px;
            padding: 5px 15px;
            border-radius: 30px;
            margin-right: 10px;
            vertical-align: middle;
            border: 1px solid #bbf7d0;
        }

        .motivational-msg {
            color: #636e72;
            margin-top: 10px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-indicator {
display: flex;
            align-items: center;
            gap: 15px;
        }

        .status-dot {
            height: 12px;
            width: 12px;
            background-color: #2ecc71;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px #2ecc71;
        }

        /* --- CARDS --- */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .stat-card {
            background: var(--white);
            padding: 30px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            transition: 0.3s;
            border: 1px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-green);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            background: #f0f4f2;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary-green);
        }

        .stat-info h3 {
            font-size: 15px;
            color: #b2bec3;
            margin-bottom: 5px;
        }

        .stat-info .number {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { width: 80px; }
            .sidebar span, .sidebar h2, .sidebar-brand span { display: none; }
            .content { margin-right: 80px; width: calc(100% - 80px); }
            .header { flex-direction: column; align-items: flex-start; gap: 20px; }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-seedling"></i>
            <span>AgroDz</span>
        </div>

        <a href="#" class="active">
            <i class="fas fa-th-large"></i> <span>الرئيسية</span>
        </a>

        <a href="{{ route('produits.index') }}">
            <i class="fas fa-box-open"></i> <span>إدارة المنتجات</span>
        </a>

        <a href="{{ route('distributeur.profile') }}">
            <i class="fas fa-user-circle"></i> <span>الملف الشخصي</span>
        </a>

        <!-- تسجيل الخروج -->
        <a href="{{ route('logout') }}" class="logout-btn"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="content">

        <!-- HEADER WITH PROFESSIONAL WELCOME -->
        <header class="header">
            <div class="welcome-section">
                <h2 class="welcome-text">
                    مرحباً بك مجدداً، <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="badge">موزع معتمد</span>
                </h2>
                <p class="motivational-msg">
                    <i class="fas fa-chart-line"></i> 
                    نظام AgroDz جاهز لمساعدتك في تتبع مخزونك وتسهيل عمليات التوزيع اليومية بدقة.
                </p>
            </div>
            
            <div class="status-indicator">
                <div class="date-badge" style="text-align: left;">
                    <span id="dateText" style="display: block; font-weight: bold; color: var(--primary-dark);"></span>
                    <small style="color: #636e72;"><span class="status-dot"></span> النظام متصل الآن</small>
                </div>
            </div>
        </header>

        <!-- STATS CARDS -->
        <div class="cards-container">

            <div class="stat-card">
                <div class="card-icon">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <div class="stat-info">
        <h3>إجمالي منتجاتي</h3> 
        <div class="number">{{ $totalProduits }}</div>
    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="card-icon">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <div class="stat-info">
                    <h3>الطلبات الجديدة</h3>
                    <div class="number">{{ $totalCommandes ?? '0' }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="card-icon">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>
                <div class="stat-info">
                    <h3>قيد المعالجة</h3>
                    <div class="number">5</div>
                </div>
            </div>

        </div>

    </main>

    <script>
        // عرض التاريخ اليومي ي
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('dateText').innerHTML = new Date().toLocaleDateString('fr-SA', dateOptions);
    </script>

</body>
</html>