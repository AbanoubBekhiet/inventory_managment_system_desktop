<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('CSS/imports_view.css') }}" rel="stylesheet">
    <script src="{{ asset('jquery.min.js')  }}"></script>
    @vite(['resources/js/app.js', 'resources/js/imports_view.js'])



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


                
            <table>
                <thead>
                    <tr>
                        <th>الصنف </th>
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
    <div class="add_quantity_main">
        <div class="product">
            <i class="fa-solid fa-circle-xmark" style="color:red;width:30px;height:30px;"></i>
            <form id="form_of_data" action="" method="post">
                @csrf
                @method("PUT")
                <div>
                    <label for="packets_number"> ادخل عدد الكراتين</label>
                    <input id="input_packets_number" type="text" name="packets_number" value="{{old('packets_number')}}">
                </div>
                <div>
                    <label for="number_of_pieces">ادخل عدد القطع  </label>
                    <input id="input_number_of_pieces" type="number" name="number_of_pieces" id="number_of_pieces" value="{{old('number_of_pieces')}}" >
                </div>
            <input id="submit_button" type="submit" value="إضافة">
            </form>
        </div>
    </div>


<script>

    let products = @json($products);
</script>

</body>
</html>

