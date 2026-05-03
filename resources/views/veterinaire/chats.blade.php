<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroDz - المحادثات</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            --primary-dark: #14532d;
            --accent-green: #16a34a;
            --bg-light: #f1f5f9;
            --white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            margin: 0;
            padding: 0;
            color: #334155;
        }

        .chat-layout {
            max-width: 1000px;
            margin: 30px auto;
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 85vh;
        }

        /* رأس الصفحة */
        .chat-header {
            background: var(--primary-dark);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header h2 {
            margin: 0;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .back-btn {
            color: white;
            text-decoration: none;
            font-size: 14px;
            opacity: 0.8;
            transition: 0.3s;
        }

        .back-btn:hover { opacity: 1; }

        /* قائمة المحادثات */
        .chat-list {
            overflow-y: auto;
            flex-grow: 1;
        }

        .chat-item {
            display: flex;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 1px solid #f1f5f9;
            text-decoration: none;
            color: inherit;
            transition: 0.3s;
            gap: 15px;
        }

        .chat-item:hover {
            background-color: #f8fafc;
        }

        .chat-item.unread {
            background-color: #f0fdf4;
            border-right: 4px solid var(--accent-green);
        }

        .avatar {
            width: 55px;
            height: 55px;
            background: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--primary-dark);
            flex-shrink: 0;
        }

        .chat-info {
            flex-grow: 1;
        }

        .chat-info-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .farmer-name {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 16px;
        }

        .chat-time {
            font-size: 12px;
            color: #94a3b8;
        }

        .last-message {
            font-size: 14px;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 400px;
        }

        /* شارات التنبيه */
        .status-badge {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #cbd5e1;
        }

        .status-online { background: #22c55e; }

        .unread-count {
            background: var(--accent-green);
            color: white;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: bold;
        }

        /* حالة فارغة */
        .empty-state {
            text-align: center;
            padding: 100px 20px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 60px;
            margin-bottom: 20px;
            display: block;
        }

        @media (max-width: 600px) {
            .chat-layout { margin: 0; height: 100vh; border-radius: 0; }
            .last-message { max-width: 200px; }
        }
    </style>
</head>
<body>

<div class="chat-layout">
    <!-- Header -->
    <div class="chat-header">
        <h2><i class="fas fa-comments"></i> مركز الرسائل والمحادثات</h2>
        <a href="{{ route('veterinaire.dashboard') }}" class="back-btn">
            <i class="fas fa-chevron-left"></i> العودة للرئيسية
        </a>
    </div>

    <!-- البحث (اختياري) -->
    <div style="padding: 15px 30px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
        <div style="position: relative;">
            <i class="fas fa-search" style="position: absolute; right: 15px; top: 12px; color: #94a3b8;"></i>
            <input type="text" placeholder="البحث عن فلاح أو مربي..." 
                   style="width: 100%; padding: 10px 40px 10px 15px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; font-size: 14px;">
        </div>
    </div>

    <!-- قائمة المحادثات -->
    <div class="chat-list">
        
        @forelse($chats ?? [] as $chat) <!-- افترضت وجود متغير $chats -->
            <a href="/chat/{{ $chat->id }}" class="chat-item {{ $chat->unread ? 'unread' : '' }}">
                <div class="avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="chat-info">
                    <div class="chat-info-top">
                        <span class="farmer-name">{{ $chat->user->name }}</span>
                        <span class="chat-time">{{ $chat->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="chat-info-top">
                        <span class="last-message">{{ $chat->last_message }}</span>
                        @if($chat->unread)
                            <span class="unread-count">جديد</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <!-- مثال تجريبي في حال كانت القائمة فارغة حالياً -->
            <a href="#" class="chat-item unread">
                <div class="avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="chat-info">
                    <div class="chat-info-top">
                        <span class="farmer-name">محمد قالمة (مربي)</span>
                        <span class="chat-time">منذ 5 دقائق</span>
                    </div>
                    <div class="chat-info-top">
                        <span class="last-message">دكتور، هل دواء الـ Produit متوفر حالياً؟ عندي بقرة تعاني من...</span>
                        <span class="unread-count">1</span>
                    </div>
                </div>
            </a>

            <a href="#" class="chat-item">
                <div class="avatar">
                    <i class="fas fa-user-alt"></i>
                </div>
                <div class="chat-info">
                    <div class="chat-info-top">
                        <span class="farmer-name">أحمد من ميلة</span>
                        <span class="chat-time">أمس</span>
                    </div>
                    <div class="chat-info-top">
                        <span class="last-message">شكراً جزيلاً يا دكتور، الحالة تحسنت كثيراً بعد العلاج.</span>
                        <div class="status-badge status-online"></div>
                    </div>
                </div>
            </a>
        @endforelse

    </div>
</div>

</body>
</html>