@extends('layouts.app')

@section('content')

<style>
body {
    background: #f4f6f9;
}

.container {
    max-width: 1100px;
    margin: auto;
}

.title {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 20px;
}

/* card */
.card {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-3px);
}

/* text */
.label {
    font-weight: bold;
}

.small {
    color: gray;
    font-size: 13px;
}

/* inputs */
input, textarea {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

/* button */
.btn {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 8px;
    background: #28a745;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

.btn:hover {
    background: #218838;
}

/* result box */
.result {
    background: #f1f3f5;
    padding: 10px;
    border-radius: 10px;
}
</style>

<div class="container">

    <div class="title">📋 إدارة الاستشارات</div>

    @foreach($consultations as $c)

    <div class="card">

        <div><span class="label">👨‍🌾 الفلاح:</span> {{ $c->eleveur->name ?? '---' }}</div>

        <div><span class="label">📌 السبب:</span> {{ $c->motif }}</div>

        <div class="small">📅 {{ $c->date_demande }}</div>

        <hr>

        @if(!$c->date_consultation)

            <label>📅 تاريخ الاستشارة</label>
            <input type="date" id="date{{ $c->id }}">

            <label>⚠️ درجة الحالة</label>
            <input type="text" id="degree{{ $c->id }}">

            <label>🩺 التشخيص</label>
            <textarea id="diag{{ $c->id }}"></textarea>

            <button class="btn" onclick="updateConsultation({{ $c->id }})">
                ✔ تأكيد
            </button>

        @else

            <div class="result">
                ✔ الموعد: {{ $c->date_consultation }} <br>
                ⚠️ الحالة: {{ $c->degree }} <br>
                🩺 التشخيص: {{ $c->diagnostique ?? '---' }}
            </div>

        @endif

    </div>

    @endforeach

</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
function updateConsultation(id){

    let date = document.getElementById('date'+id).value;
    let degree = document.getElementById('degree'+id).value;
    let diag = document.getElementById('diag'+id).value;

    if(!date || !degree){
        alert("⚠️ لازم تعمر المعلومات");
        return;
    }

    fetch(`/consultation/${id}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            date_consultation: date,
            degree: degree,
            diagnostique: diag
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert("✔ تم التحديث");
            location.reload();
        }else{
            alert("❌ خطأ");
        }
    });
}
</script>

@endsection