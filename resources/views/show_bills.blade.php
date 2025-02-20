<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{asset("CSS/show_bills.css")}}" >
    <title>Document</title>
    {{-- @vite(['resources/js/app.js', 'resources/js/show_bills.js']) --}}



</head>
<body style="margin:0px;direction:rtl;background-color:#6c7a89;font-size:23px">
    <div class="header">
        <div>مرحباً , <span style="color:#fff">{{Auth::user()->name}} </span></div>
        <p>شركة ابو الدهب للتجارة</p>

    </div>
    <div class="sidebar">
        <ul>
            <li><a href="">الملف الشخصي</a></li>
            <li><a href="{{route('customers')}}">الزبائن</a></li>
            <li><a href="{{route('products_view')}}">المنتجات</a></li>
            <li><a href="{{route('imports_view')}}">إضافة الوارد</a></li>
            <li><a href="{{route(name: 'categories')}}">الفئات</a></li>
            <li><a href="{{route(name: 'show_bills')}}">الفواتير </a></li>
            <li><a href="{{route(name: 'make_bills')}}">عمل فاتورة</a></li>
            <li><a href="{{route( 'statistics')}}">إحصائيات</a></li>
        </ul>
    </div>

    <div class="body">
        <div class="content">
            <div class="filters">
                <a href="{{route('exportBills')}}"><button class="Export">إنشاء ملف إكسيل لل فواتير</button></a>

                <label class="day">
                    <input  type="checkbox" name="day" id="day" checked>
                    <span class="checkmark"></span>
                    فواتير هذا اليوم فقط
                </label>
        
                <div class="customer_select">
                    <label for="search"> اختيار فواتير عميل معين</label>
                    <div id="search_div">
                        <input type="text" name="search" id="search" placeholder="ادخل اسم العميل">
                    </div>
                </div>
            </div>

            <div class="bills">
                <table>
                    <thead>
                        <tr>
                            <th> رقم الفاتورة </th>
                            <th>اسم العميل </th>
                            <th>رقم الهاتف</th>
                            <th>قيمة الفاتورة الاجمالية</th>
                            <th>التاريخ</th>
                            <th>رؤية مكسب الفاتورة</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody id="bill-table-body">
                        @foreach ($bills_with_limit as $bill =>$index)
                            <tr>
                                <td>{{$bill+1}}</td>
                                <td>{{$index->cus_name}}</td>
                                <td>{{$index->phone_number}}</td>
                                <td>{{$index->total_price}}</td>
                                <td>{{$index->created_at}}</td>
                                <td><a href="{{route("bill_binefits",$index->id)}}">عرض مكسب الفاتورة</a></td>
                                <td><a href="{{route("show_specific_bill",$index->id)}}">عرض الفاتورة</a></td>
                            </tr>
                        @endforeach
                    </tbody>  
                </table>
            </div>
        </div>
        
    </div>




    <script>
        let bills_no_limit=@json($bills_no_limit);
        let bills_with_limit=@json($bills_with_limit);
    </script>
    <script src="{{ asset('jquery.min.js')  }}"></script>
    <script src="{{ asset('js/show_bills.js')  }}"></script>
</body>
</html>