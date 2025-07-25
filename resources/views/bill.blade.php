<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset("CSS/bill.css")}}" media="all">
    <link rel="stylesheet" href="{{asset("all.min.css")}}" media="all">
    <script src="{{asset("jquery.min.js")}}"></script>
    @vite(['resources/js/app.js', 'resources/js/bill.js'])
</head>
<body>
    <div class="header no-print">
        <div>مرحباً , <span style="color:#fff">{{Auth::user()->name}} </span></div>
        <p>شركة ابو الدهب للتجارة</p>
    </div>
    <div class="sidebar no-print">
        <ul>
            <li><a href="{{route('customers')}}">الزبائن</a></li>
            <li><a href="{{route('products_view')}}">المنتجات</a></li>
            <li><a href="{{route('imports_view')}}">إضافة الوارد</a></li>
            <li><a href="{{route(name: 'categories')}}">الفئات</a></li>
            <li><a href="{{route(name: 'show_bills')}}">الفواتير </a></li>
            <li><a href="{{route(name: 'make_bills')}}">عمل فاتورة</a></li>
            <li><a href="{{route( 'statistics')}}">إحصائيات</a></li>
        </ul>
    </div>
<div class="content" style="position:relative;" >




    <div class="date">
                @php
                    $date = new DateTime($products[0]->created_at);
                    $day = $date->format('Y-m-d');  
                @endphp
                <div class="date" style="position:relative;right:10px;top:10px;">التاريخ : {{$day}}</div>
    </div>


<div dir="rtl" class="header2" style="font-size:19px;">
    <div style="display:flex; justify-content:space-between;">
        <p style="font-size:25px;">شركة ابو الدهب للتجارة والتوزيع</p>
        <div style="display:flex; gap:10px; align-items:flex-start;">
            <div>ميلاد :</div>
            <div style="display:flex; flex-direction:column;">
                <span><i class="fa-solid fa-phone-volume"></i> <i class="fa-brands fa-whatsapp"></i>&nbsp;&nbsp;01200277612</span>
                <span><i class="fa-solid fa-phone-volume"></i> <i class="fa-brands fa-whatsapp"></i>&nbsp;&nbsp;01287573679</span>
            </div>
        </div>
    </div>

    <div style="display:flex; justify-content:space-evenly; margin-top:10px;">
        <span>اسم العميل : {{$products[0]->cus_name}}</span>
        <span>رقم الهاتف : {{$products[0]->phone_number}}</span>
        <span>عنوان العميل : {{$products[0]->address}}</span>
    </div>

    <hr style="height:2px; background-color:black; margin-top:20px;">
</div>



        
        
        
        
        
        
        
        
        
        <table >
            <thead >
                <tr>
                    <th> الرقم </th>
                    <th> المنتج </th>
                    <th> عدد الكراتين </th>
                    <th>سعر الكرتونة</th>
                    <th>عدد القطع</th>
                    <th>سعر القطعة</th>
                    <th>سعر بيع القطعة للمستهلك</th>
                    <th>إجمالي سعر المنتج </th>
                </tr>
            </thead>
            <tbody id="products-table-body">
               @foreach ($products as $product =>$index)
                   <tr>
                        <td>{{$product+1}}</td>
                        <td >{{$index->name}} * {{$index->n_pieces_in_packet}} ق </td>
                        <td>{{$index->number_of_packets}}  </td>
                        <td>{{$index->packet_price}}  </td>
                        <td>{{$index->number_of_pieces}}  </td>
                        <td>{{$index->piece_price}}  </td>
                        <td style="width:15px"> {{$index->selling_customer_piece_price}}  </td>
                        <td>{{$index->total_product_price}}  </td>
                   </tr>
               @endforeach
            </tbody>  
        </table>

        <div class="total" style="margin-top:10px;width:fit-content;font-size:20px;border:2px solid black;padding:10px 12px; ">إجمالي الفاتورة : {{$products[0]->total_price}}</div>
        <button class="no-print" style="margin-bottom:100px;">إطبع </button>
    </div>




</body>
</html>



