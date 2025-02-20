<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('CSS/customers.css') }}" rel="stylesheet">
    {{-- @vite(['resources/js/app.js', 'resources/js/customers.js']) --}}







</head>
<body style="margin:0px;direction:rtl;background-color:#6c7a89;font-size:23px">
    <div class="header">
        <div>مرحباً , <span style="color:#fff">{{Auth::user()->name}} </span></div>
        <p>شركة ابو الدهب للتجارة</p>
        <div id="search_div">
            <i class="fas fa-search"></i>        
            <input type="text" name="search" id="search" placeholder="ابحث عن عميل">
        </div>
    </div>

    <div class="body">

        @component("components.sidebar")
        @endcomponent
        <div class="content">
            <a href="{{route('exporCustomers')}}"> <button class="Export">إنشاء ملف إكسيل لل منتجات</button></a>

            <div style="display:flex;justify-content:space-evenly;align-items:center">
                <p> المنتجات المتوفرة في النظام</p> 
                <input id="addCustomer" type="button" value="إضافة عميل" style="padding:10px;font-size:20px;background-color:#6c7a89;border:0px;color:#fff;border-radius:5px;">
            </div>
                
            <table>
                <thead>
                    <tr>
                        <th> رقم العميل </th>
                        <th>اسم العميل </th>
                        <th>العنوان</th>
                        <th> رقم الهاتف </th>
                        <th> مشترياته</th>
                        <th> عمليات</th>
                    </tr>
                </thead>
                <tbody id="customers-table-body">
                
                </tbody>  
            </table>

            


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
    
    <div class="add_customer_main">
        <div class="customer">
            <i class="fa-solid fa-circle-xmark" style="color:red;width:30px;height:30px;"></i>
            <form id="form_of_data" action="{{route('add_customer')}}" method="post">
                @csrf
                <div>
                    <label for="cus_name">اسم العميل</label>
                    <input id="input_cus_name" type="text" name="cus_name" value="{{old('cus_name')}}">
                </div>
                <div>
                    <label for="phone_number">  رقم الهاتف</label>
                    <input id="input_phone_number" type="number" name="phone_number" id="phone_number" value="{{old('phone_number')}}" >
                </div>
                <div>
                    <label for="address">  العنوان</label>
                    <input id="input_address" type="text" name="address" id="address" value="{{old('address')}}">
                </div>
                
              <input id="submit_button" type="submit" value="إضافة">
            </form>
        </div>
    </div>




    <script >
        let customers = @json($customers);
    </script>
    <script src="{{ asset('jquery.min.js') }}"></script>
    <script src="{{ asset('sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/customers.js') }}"></script>
            
</body>
</html>

