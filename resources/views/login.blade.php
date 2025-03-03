<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body style="direction:rtl;background-color:#a3c6c4;height:90vh">
    <p style="padding:10px;font-size:24px;color:#fff;
    text-align:center;background-color:#6c7a89;display:block">  شركة ابو الدهب لتجارة الخردوات ومستحضرات التجميل والمنظفات والورقيات</p>


    <div style="position:relative;top:45%;left:50%;transform:translate(-50%,-50%);background-color:red;
    padding:30px;display:flex;flex-direction:column;align-items:center;background-color:#a3c6c4">
        <div style="padding:30px;background-color:#354649;padding:50px;border-radius:5px;
        font-size:25px;color:#a3c6c4;">
        <p style="text-align:center;text-decoration:underline">تسجيل دخول</p>
            <form action="{{route('login_fun')}}" method="post" >
                @csrf
                <label for="name">اسم المستخدم</label>
                <input type="text" name="name" id="name">
                <label for="password">الرقم السري</label>
                <input type="password" name="password" id="password">
                <input type="submit" value="تسجيل دخول">
            </form>
@if ($con)
<a  href="{{route('register_view')}}">تسجيل حساب جديد</a>
@endif
            @if(session()->has('error'))
                <div style="position: absolute; padding: 20px; background-color: tomato; right:80px; top: 6%;color:#fff">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>



</body>
</html>

<style>
    form{
        display:flex;
        flex-direction:column;
        align-items:flex-end;
        justify-content:space-between;
        background-color:#6c7a89;
        padding:20px;
        border-radius:5px;
        color:#fff;
        margin-top:10px;
        font-size:22px;
    }
    form input[type='text'],form input[type='password']{
        padding:10px 10px 10px 50px ;
        margin-block:10px;
        font-size:20px;
    }
    form input[type='submit']{
        padding:10px 50px ;
        margin-block:10px;
        font-size:20px;
        color:#354649;
        position:relative;
        left:50%;
        transform:translateX(-50%);
    }
    a{
        color:white;
        text-decoration: none;
    }
</style>