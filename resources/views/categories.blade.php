<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('CSS/categories.css') }}" rel="stylesheet">
    <script src="{{ asset('jquery.min.js')  }}"></script>
    @vite(['resources/js/app.js', 'resources/js/category.js'])
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
            <div style="display:flex;justify-content:space-evenly;align-items:center">
                <p>فئات المنتجات المتوفرة في النظام</p> 
                <input id="addCategory" type="button" value="إضافة فئة" style="padding:10px;font-size:20px;background-color:#6c7a89;border:0px;color:#fff;border-radius:5px;">
            </div>
                
            <table>
                <tr>
                    <td>الرقم</td>
                    <td>الفئة</td>
                </tr>
                @foreach ($categories as $category =>$index)
                    <tr>
                        <td>{{$category+1}}</td>
                        <td>{{$index->name}}</td>
                    </tr>
                @endforeach    
            </table>




            @if(session("message"))
            <p class="error-message">{{session("message")}}</p>
            @endif
        
            @error("category_name")
            <p class="error-message">{{"$message"}}</p>
            @enderror
    
        </div>
    </div>
    <div class="add_category_main">
        <div class="category">
            
            <i class="fa-solid fa-circle-xmark" style="color:red;width:30px;height:30px;"></i>
            <form action="{{route('add_category')}}" method="post">
                @csrf
                <label for="category_name">ادخل اسم الفئة</label>
                <input type="text" name="category_name" id="category_name" placeholder="اسم الفئة">
                <input type="submit" value="إضافة">
            </form>
        </div>
    </div>

</body>
</html>


