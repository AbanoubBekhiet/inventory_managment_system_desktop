<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('CSS/bill_benifit.css') }}" rel="stylesheet">

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
            <button id="backButton" > العودة للصفحة السابقة</button>
            <div style="display:flex;justify-content:space-evenly;align-items:center">
                <div> مكسب هذه الفاتورة</div>
                <div>{{$total_benefits_specific_bill}}ج</div>
            </div>
                
        




    
        </div>
    </div>

    <script>
        document.getElementById("backButton").addEventListener("click", function() {
            window.history.back();
        });
    </script>
</body>
</html>


