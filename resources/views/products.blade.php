<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('CSS/products.css') }}" rel="stylesheet">
    {{-- @vite(['resources/js/app.js', 'resources/js/products.js']) --}}
    {{-- <script src="{{ asset('jquery.min.js')  }}"></script> --}}



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

        @component("components.sidebar")
        @endcomponent
        <div class="content">
            <div style="display:flex;justify-content:space-evenly;align-items:center">
                <p> المنتجات المتوفرة في النظام</p> 
                <input id="addProduct" type="button" value="إضافة منتج" style="padding:10px;font-size:20px;background-color:#6c7a89;border:0px;color:#fff;border-radius:5px;">
            </div>
                
            <table>
                <thead>
                    <tr>
                        <th>الصنف </th>
                        <th>عدد القطع في العلبة</th>
                        <th>سعر استلام الكرتونة </th>
                        <th>سعر بيع الكرتونة</th>
                        <th>سعر بيع القطعة</th>
                        <th>  عدد الكراتين المتوفرة</th>
                        <th> عدد القطع او الاكياس المتوفرة</th>
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
                    <input id="input_product_name" type="text" name="product_name" value="{{old('product_name')}}">
                </div>
                <div>
                    <label for="product_category">اختر فئة المنتج</label>
                    <select id="input_product_category" name="category" id="category" value="{{old('category_name')}}">
                        @foreach ($categories as $categorie)
                        <option value="{{$categorie->id}}">{{$categorie->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="number_of_pieces">ادخل عدد القطع في العلبة</label>
                    <input id="input_number_of_pieces" type="number" name="number_of_pieces" id="number_of_pieces" value="{{old('number_of_pieces')}}" >
                </div>
                <div>
                    <label for="original_price">ادخل سعر الاستلام</label>
                    <input id="input_original_price" type="text" name="original_price" id="original_price" value="{{old('original_price')}}">
                </div>
                <div>
                    <label for="packet_selling_price">ادخل سعر بيع الكرتونة</label>
                    <input id="input_packet_selling_price" type="number" name="packet_selling_price" id="packet_selling_price" value="{{old('packet_selling_price')}}">
                </div>
                <div>
                    <label for="piece_selling_price">ادخل سعر بيع القطعة</label>
                    <input id="input_piece_selling_price" type="number" name="piece_selling_price" id="piece_selling_price" value="{{old('piece_selling_price')}}">
                </div>
                <div style="margin-bottom:60px;">
                    <label for="number_of_exciting_packets">ادخل عدد القطع الموفرة </label>
                    <input id="input_number_of_exciting_packets" type="number" name="number_of_exciting_packets" id="number_of_exciting_packets" value="{{old('number_of_exciting_packets')}}">
                </div>
                <div style="margin-bottom:60px;">
                    <label for="existing_number_of_pieces">ادخل عدد الكراتين الموفرة </label>
                    <input id="existing_number_of_pieces" type="number" name="existing_number_of_pieces" id="existing_number_of_pieces" value="{{old('existing_number_of_pieces')}}">
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
    <script src="{{ asset('jquery.min.js')  }}"></script>
    <script src="{{ asset('js/products.js')  }}"></script>
</body>
</html>

