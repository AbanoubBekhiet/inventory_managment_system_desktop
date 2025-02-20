<div class="sidebar">
    <ul>
        <li><a href="{{route('customers')}}">الزبائن</a></li>
        <li><a href="{{route('products_view')}}">المنتجات</a></li>
        <li><a href="{{route('imports_view')}}">إضافة الوارد</a></li>
        <li><a href="{{route( 'categories')}}">الفئات</a></li>
        <li><a href="{{route( 'show_bills')}}">الفواتير</a></li>
        <li><a href="{{route( 'make_bills')}}">عمل فاتورة</a></li>
        <li><a href="{{route( 'statistics')}}">إحصائيات</a></li>
    </ul>
</div>


<style>

.sidebar ul li{
    margin-bottom:10px;
}
.sidebar ul li:hover{
    margin-right:10px;
    transition-duration: 0.5s;
}

.sidebar{
    background-color:#eec8ae;

    padding:10px;
    padding-top:30px;
    width:14%;
    border-radius:15px ;
}
.sidebar ul{
    margin:0px;
    list-style:none;
}
.sidebar ul a{
    text-decoration: none;
    color:#000;
    font-size:23px;
}
</style>