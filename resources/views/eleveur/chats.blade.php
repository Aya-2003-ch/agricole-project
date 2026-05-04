<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>المحادثات | AgroDz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #1b4332;
            --accent-green: #2d6a4f;
            --bg-light: #f1f5f9;
            --chat-bg: #ffffff;
            --sender-msg: #dcfce7;
            --receiver-msg: #f8fafc;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-light);
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            max-width: 1000px;
            margin: 20px auto;
            width: 95%;
            background: var(--chat-bg);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* رأس المحادثة */
        .chat-header {
            padding: 15px 25px;
            background: var(--primary-green);
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .chat-header img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid white;
        }

        /* منطقة الرسائل */
        .messages-area {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); /* خلفية خفيفة */
        }

        .message {
            max-width: 70%;
            padding: 12px 18px;
            border-radius: 15px;
            font-size: 15px;
            line-height: 1.5;
            position: relative;
        }

        /* رسالة المرسل (أنا) */
        .message.sent {
            align-self: flex-start;
            background: var(--sender-msg);
            color: var(--primary-green);
            border-bottom-right-radius: 2px;
            border: 1px solid #bdf1d0;
        }

        /* رسالة المستقبل (الطرف الآخر) */
        .message.received {
            align-self: flex-end;
            background: var(--receiver-msg);
            color: #334155;
            border-bottom-left-radius: 2px;
            border: 1px solid #e2e8f0;
        }

        .message-time {
            display: block;
            font-size: 10px;
            margin-top: 5px;
            opacity: 0.7;
        }

        /* منطقة الإدخال */
        .chat-footer {
            padding: 20px;
            background: white;
            border-top: 1px solid #e2e8f0;
        }

        .input-group {
            display: flex;
            gap: 10px;
        }

        .input-group input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #f1f5f9;
            border-radius: 30px;
            outline: none;
            transition: 0.3s;
            font-size: 15px;
        }

        .input-group input:focus {
            border-color: var(--accent-green);
            background: #f8fafc;
        }

        .btn-send {
            background: var(--accent-green);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .btn-send:hover {
            background: var(--primary-green);
            transform: scale(1.1);
        }

        .btn-back {
            color: white;
            text-decoration: none;
            font-size: 20px;
            margin-left: 10px;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <!-- رأس المحادثة -->
    <div class="chat-header">
        <a href="{{ route('eleveur.dashboard') }}" class="btn-back"><i class="fas fa-arrow-right"></i></a>
        <img src="https://ui-avatars.com/api/?name=Vet+User&background=2d6a4f&color=fff" alt="User">
        <div>
            <h4 style="margin: 0;">الدردشة المباشرة</h4>
            <small><i class="fas fa-circle" style="color: #4ade80; font-size: 10px;"></i> متصل الآن</small>
        </div>
    </div>

    <!-- منطقة عرض الرسائل -->
    <div class="messages-area" id="chatBox">
        @forelse($messages as $msg)
            <div class="message {{ $msg->sender_id == Auth::id() ? 'sent' : 'received' }}">
                {{ $msg->content }}
                <span class="message-time">{{ $msg->created_at->format('H:i') }}</span>
            </div>
        @empty
            <div style="text-align: center; color: #94a3b8; margin-top: 50px;">
                <i class="fas fa-comments fa-3x"></i>
                <p>ابدأ المحادثة الآن مع البيطري</p>
            </div>
        @endforelse
    </div>

    <!-- منطقة الكتابة -->
    <div class="chat-footer">
        <form action="{{ route('eleveur.messages.send') }}" method="POST" class="input-group">
            @csrf
            {{-- سنحتاج لإرسال معرف المستقبل مخفياً --}}
            <input type="hidden" name="receiver_id" value="{{ $receiver_id ?? '' }}">
            
            <input type="text" name="content" placeholder="اكتب رسالتك هنا..." required autocomplete="off">
            <button type="submit" class="btn-send">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script>
    // لجعل التمرير ينزل لآخر رسالة تلقائياً
    const chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

</body>
</html>