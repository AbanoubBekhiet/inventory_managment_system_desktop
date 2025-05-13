<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('CSS/products.css') }}" rel="stylesheet">
    <script src="{{ asset('jquery.min.js')  }}"></script>
    @vite(['resources/js/app.js', 'resources/js/products.js'])



</head>
<body style="margin:0px;direction:rtl;background-color:#6c7a89;font-size:23px">
    <div class="header">
        <div>مرحباً , <span style="color:#fff">{{Auth::user()->name}} </span></div>
        <p>شركة ابو الدهب للتجارة</p>
        <div id="search_div">
            <i class="fas fa-search"></i>        
            <input type="text" name="search" id="search" placeholder="ابحث عن منتج">
        </div>
    </div>

    <div class="body">

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
        <div class="content">
            <div class="buttons_div">
                <a href="{{route('exportProducts')}}"><button class="Export">إنشاء ملف إكسيل لل منتجات</button></a>
                <div style="background-color:#6c7a89;padding:10px;border-radius:5px;color:#fff;">
                    <label for="cat">اختر المنتجات بناءا علي الفئة</label>
                <select name="cat" id="cat">
                    @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                </div>
                
                <form action="{{ route('backup.database') }}" method="GET">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        عمل نسخة احتياطية من قاعدة البيانات كاملة
                    </button>
                </form>
            </div>
            
            <div style="display:flex;justify-content:space-evenly;align-items:center">
                <p> المنتجات المتوفرة في النظام</p> 
                <input id="addProduct" type="button" value="إضافة منتج" style="padding:10px;font-size:20px;background-color:#6c7a89;border:0px;color:#fff;border-radius:5px;">
            </div>
                
            <table id="products">
                <thead>
                    <tr>
                        <th>رقم المنتج </th>
                        <th>الصنف </th>
                        <th>عدد القطع في العلبة</th>
                        <th>سعر استلام الكرتونة </th>
                        <th>سعر بيع الكرتونة</th>
                        <th>سعر بيع القطعة</th>
                        <th>  عدد الكراتين المتوفرة</th>
                        <th> عدد القطع او الاكياس المتوفرة</th>
                        <th> سعر القطعة للمستهلك</th>
                        <th> هل المنتج يقبل التجزأة</th>
                        <th> العمليات</th>
                    </tr>
                </thead>
                <tbody id="products-table-body">
                
                </tbody>  
            </table>
            

            


            @if(session("message"))
            <p class="error-message ">{{session("message")}}</p>
            @endif

            @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        @if($error)
                            <p   class="error-message" style="display:none;">{{ $error }}</p>

                        @endif
                    @endforeach
            @endif


            <p  id="message-container" class="error-message" style="display:none"></p>
            
            
    
        </div>
    </div>
    <div class="add_product_main">
        <div class="product">
            <i class="fa-solid fa-circle-xmark" style="color:red;width:30px;height:30px;"></i>
            <form id="form_of_data" action="{{route('add_product')}}" method="post">
                @csrf
                <div>
                    <label for="product_name">اسم منتج</label>
                    <input id="input_product_name" type="text" name="product_name" >
                </div>
                <div>
                    <label for="product_category">اختر فئة المنتج</label>
                    <select id="input_product_category" name="category" id="category" >
                        @foreach ($categories as $categorie)
                        <option value="{{$categorie->id}}">{{$categorie->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="number_of_pieces">ادخل عدد القطع في الكرتونة</label>
                    <input id="input_number_of_pieces"  step="any" type="number" name="number_of_pieces" id="number_of_pieces"  >
                </div>
                <div>
                    <label for="original_price">ادخل سعر استلام الكرتونة</label>
                    <input id="input_original_price"  step="any" type="text" name="original_price" id="original_price" >
                </div>
                <div>
                    <label for="packet_selling_price">ادخل سعر بيع الكرتونة</label>
                    <input id="input_packet_selling_price"  step="any" type="number" name="packet_selling_price" id="packet_selling_price" >
                </div>
                <div>
                    <label for="piece_selling_price">ادخل سعر بيع القطعة</label>
                    <input id="input_piece_selling_price"  step="any" type="number" name="piece_selling_price" id="piece_selling_price" >
                </div>
                <div style="margin-bottom:60px;">
                    <label for="number_of_exciting_packets">ادخل عدد القطع الموفرة </label>
                    <input id="input_number_of_exciting_packets"  step="any" type="number" name="number_of_exciting_packets" id="number_of_exciting_packets" >
                </div>
                <div style="margin-bottom:60px;">
                    <label for="existing_number_of_pieces">ادخل عدد الكراتين الموفرة </label>
                    <input id="existing_number_of_pieces"  step="any" type="number" name="existing_number_of_pieces" id="existing_number_of_pieces" >
                </div>
                <div style="margin-bottom:60px;">
                    <label for="selling_customer_piece_price">ادخل سعر بيع القطعة للمستهلك   </label>
                    <input id="selling_customer_piece_price"  step="any" type="number" name="selling_customer_piece_price" id="selling_customer_piece_price" >
                </div>
                <div id="accept_pieces_div">
                    <label>هل المنتج يقبل التجزأة</label>
                    <label for="accept_pieces1">نعم</label>
                    <input style="padding:20px;" id="accept_pieces1" type="radio" name="accept_pieces" value="1">
                    <label for="accept_pieces2">لا</label>
                    <input style="padding:20px;" id="accept_pieces2" type="radio" name="accept_pieces" value="0">
                </div>
              <input id="submit_button" type="submit" value="إضافة">
            </form>
        </div>
    </div>


<script>

    let products = @json($products);
</script>
    <script src="{{ asset('sweetalert2.all.min.js') }}"></script>

</body>
</html>

