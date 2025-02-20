<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('CSS/statistics.css') }}" rel="stylesheet">

</head>
<body style="margin:0px;direction:rtl;background-color:#6c7a89;font-size:23px">
    <div class="header">
        <div>مرحباً , <span style="color:#fff">{{Auth::user()->name}} </span></div>
        <p>شركة ابو الدهب للتجارة</p>
    </div>

    <div class="body">
        @component("components.sidebar")
        @endcomponent
        <div class="content">
            <div style="margin-bottom:100px;">
                <span>رأس المال الكلي بناءً علي سعر الشراء </span>
                <span> {{$total_money_based_on_original_price}} ج</span>
            </div>
            <div style="margin-bottom:100px;">
                <span>رأس المال الكلي بناءً علي سعر البيع </span>
                <span> {{$total_money_based_on_selling_price}} ج</span>
            </div>
            <hr style="position:absolute; top:220px;left:0px;width:100%;height:3px;background-color:#6c7a89;">
            <div >
                <span>مبيعات الشهر الماضي</span>
                <span> {{$total_selling_last_month}} ج</span>
            </div>
            <div >
                <span>مبيعات السنة الماضية</span>
                <span> {{$total_selling_last_year}} ج</span>
            </div>
            <div style="color:#6c7a89;background-color:#fff">
                <span>مبيعات هذا الشهر</span>
                <span> {{$total_selling_this_month}} ج</span>
            </div>
            <div style="color:#6c7a89;background-color:#fff">
                <span>مبيعات هذه السنة</span>
                <span> {{$total_selling_this_year}} ج</span>
            </div>
            <div>
                <span>  مكسب الشهر الماضي من المبيعات</span>
                <span> {{$total_benefits_last_month_number}} ج</span>
            </div>
            <div>
                <span>  مكسب السنة الماضية من المبيعات</span>
                <span> {{$total_benefits_last_year_number}} ج</span>
            </div>
            <div style="color:#6c7a89;background-color:#fff"> 
                <span>  مكسب هذا الشهر من المبيعات</span>
                <span> {{$total_benefits_this_month_number}} ج</span>
            </div>
            <div style="color:#6c7a89;background-color:#fff">
                <span>  مكسب هذه السنة من المبيعات</span>
                <span> {{$total_benefits_this_year_number}} ج</span>
            </div>
            <div style="color:#6c7a89;background-color:#fff">
                <span>  مكسب  اليوم من المبيعات</span>
                <span> {{$total_benefits_today_number}} ج</span>
            </div>
        </div>
    </div>
   
</body>
</html>
