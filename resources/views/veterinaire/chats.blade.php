@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl" style="text-align: right;">
    <h2>💬 المحادثات المباشرة</h2>
    <p>هنا ستظهر قائمة الفلاحين والمربين للتواصل معهم بخصوص الأدوية والحالات الزراعية.</p>
    
    <div class="list-group mt-4">
        <!-- مثال لمحادثة -->
        <a href="#" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">الفلاح: محمد قالمة</h5>
                <small>منذ 5 دقائق</small>
            </div>
            <p class="mb-1">دكتور، هل دواء الـ Produit متوفر حالياً؟</p>
        </a>
    </div>
</div>
@endsection