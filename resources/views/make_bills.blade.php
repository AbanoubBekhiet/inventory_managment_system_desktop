<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('CSS/bills.css') }}" rel="stylesheet">
    <script src="{{ asset('jquery.min.js') }}"></script>
    @vite(['resources/js/app.js', 'resources/js/bills.js'])


</head>


<body style="margin:0px;direction:rtl;background-color:#6c7a89;font-size:23px">
    <div class="header">
        <div>مرحباً , <span style="color:#fff">{{Auth::user()->name}} </span></div>
        <p>شركة ابو الدهب للتجارة</p>
    </div>

    <div class="sidebar">
        <ul>
            <li><a href="{{route('customers')}}">الزبائن</a></li>
            <li><a href="{{route('products_view')}}">المنتجات</a></li>
            <li><a href="{{route('imports_view')}}">إضافة الوارد</a></li>
            <li><a href="{{route( 'categories')}}">الفئات</a></li>
            <li><a href="{{route( 'show_bills')}}">الفواتير </a></li>
            <li><a href="{{route( 'make_bills')}}">عمل فاتورة</a></li>
            <li><a href="{{route( 'statistics')}}">إحصائيات</a></li>
        </ul>
    </div>
    <div class="body">


        





        <div class="content">
            <div style="display:flex;justify-content:space-evenly;align-items:center">
                <button id="choose_customer">اختيار عميل</button>
                <button class="submit_button">حفظ الفاتورة</button>
                <button id="add_product">إضافة منتج</button>

            </div>

        <p id="customer_choosen">
            <span >اسم العميل  :   </span>
            <span>لم يتم الاختيار</span>
        </p>
            <div class="bill">
                    <table id="customer_table">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>سعر الكرتونة</th>
                                <th>عدد الكراتين </th>
                                <th>سعر القطعة</th>
                                <th> عدد القطع</th>
                                <th>  السعر الاجمالي</th>
                            </tr>
                        </thead>
                        <tbody >
                        </tbody>
                </table>
                

                <table id="user_table">
                    <thead>
                        <tr>
                            <th>سعر الكرتونة الاصلي</th>
                            <th>عدد الكراتين المتوفرة</th>
                            <th>سعر القطعة الاصلي</th>
                            <th>عدد القطع المتوفرة</th>
                        </tr>
                    </thead>
                    <tbody >
                        
                    </tbody>
                </table>
            </div>
               

            <div class="total_price"><span>إجمالي الفاتورة :</span> <span>0</span></div>

        </div>




        <div class="add_product_main" >
            <div class="product">
                <div class="product_header">
                    <i class="fa-solid fa-circle-xmark" style="color:red;width:30px;height:30px;"></i>
                    <div id="search_div">
                        <input type="text" name="search" id="search" placeholder="ابحث عن منتج">
                    </div>
                </div>
                    <table>
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>سعر استلام الكرتونة</th>
                                <th>سعر بيع الكرتونة</th>
                                <th>سعر بيع القطعة </th>
                                <th> عدد الكراتين المتوفرة</th>
                            </tr>
                        </thead>
                        <tbody id="products-table-body">
                            @foreach ($products as $product )
                            <tr data-id="{{$product->id}}">
                                <td>{{$product->name}} -- {{$product->n_pieces_in_packet}} ق</td>
                                <td>{{$product->original_packet_price}}</td>
                                <td>{{$product->selling_packet_price}}</td>
                                <td>{{$product->piece_price}}</td>
                                <td>{{$product->exicting_number_of_pieces}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
       



        <div class="add_customer_main" >
            <div class="customer">
                <div class="customer_header">
                    <i class="fa-solid fa-circle-xmark" style="color:red;width:30px;height:30px;"></i>
                    <div id="search_div_customer">
                        <input type="text" name="search" id="search_customer" placeholder="ابحث عن منتج">
                    </div>
                </div>
                    <table>
                        <thead>
                            <tr>
                                <th>العميل</th>
                                <th>هاتف العميل</th>
                                <th>عنوان العميل</th>

                            </tr>
                        </thead>
                        <tbody id="customer-table-body">
                            @foreach ($customers as $customer )
                            <tr data-id="{{$customer->id}}">
                                <td >{{$customer->cus_name}}</td>
                                <td>{{$customer->phone_number}}</td>
                                <td>{{$customer->address}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
        <div  class="error-message" style="width:70%;text-align:center"></div>


    </div>
    <script>
        let products = @json($products ?? []);
        let customers = @json($customers ?? []);
    </script>
        <script src="{{ asset('sweetalert2.all.min.js') }}"></script>
</body>
</html>






