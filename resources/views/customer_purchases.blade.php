<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('CSS/customer_purchases.css') }}" rel="stylesheet">







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
            <div class="statistics">
                <p> إجمالي مبيعات هذا الشهر   {{$total_selling_this_month}}</p>
                <p> إجمالي مبيعات هذه السنة {{$total_selling_this_year}}</p>
                <p> إجمالي مبيعات الشهر الماضي {{$total_selling_last_month}}</p>
                <p> إجمالي مبيعات السنة الماضية {{$total_selling_last_year}}</p>
                <p> إجمالي ربح هذا الشهر {{$total_benefits_this_month_number->total_benefit}}</p>
                <p> إجمالي ربح هذه السنة  {{$total_benefits_this_year_number->total_benefit}}</p>
                <p> إجمالي ربح الشهر الماضي  {{$total_benefits_last_month_number->total_benefit ?? 0}}</p>
                <p> إجمالي ربح السنة الماضي  {{$total_benefits_last_year_number->total_benefit ?? 0}}</p>
            </div>           
    
  



                
            <div class="bills">
                <table>
                    <thead>
                        <tr>
                            <th>اسم العميل </th>
                            <th>رقم الهاتف</th>
                            <th>قيمة الفاتورة الاجمالية</th>
                            <th>التاريخ</th>
                            <th>مكسب الفاتورة</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody id="bill-table-body">
                        @foreach ($bills as $bill)
                            <tr>
                                <td>{{$bill->cus_name}}</td>
                                <td>{{$bill->phone_number}}</td>
                                <td>{{$bill->total_price}}</td>
                                <td>{{$bill->created_at}}</td>
                                <td><a href="{{route("bill_binefits",$bill->id)}}">عرض مكسب الفاتورة</a></td>
                                <td><a href={{route("show_specific_bill",$bill->id)}}>عرض الفاتورة</a></td>
                            </tr>
                        @endforeach
                    </tbody>  
                    <div class="mt-4">
                    </div>
                </table>
                {{ $bills->links('vendor.pagination.custom',["elements"=>$bills]) }}
            </div>

            


        @if(session("message"))
            <p   class="error-message">{{session("message")}}</p>
        @endif 

    <p  id="message-container" class="error-message"></p>
            
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @if($error)
                <p   class="error-message" style="display:none;">{{ $error }}</p>
            @endif
        @endforeach
    @endif
            
    
        </div>
    </div>
    






            
</body>
</html>

