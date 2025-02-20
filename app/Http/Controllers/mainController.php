<?php

namespace App\Http\Controllers;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Product;
class mainController extends Controller
{
    public function login_view(){
        return view("login");
    }
    public function login_fun(Request $request){
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt(["name"=>$request->name,"password"=>$request->password])){
            return redirect("products");
        }
        else{
            return redirect()->back()->with("error","خطأ في اسم المستخدم او الرقم السري");
        }
    }


    public function categories(){
        $categories=Category::select()->get();
        return view("categories",["categories"=>$categories]);
    }
    public function add_category(Request $request){
        $request->validate([
            'category_name' => 'required|string|unique:categories,name',
        ], [
            'category_name.unique' => 'الفئة موجودة بالفعل في النظام',
        ]);
        

        $category=new Category();
        $category->name=$request->category_name;
        $category->u_id=Auth::user()->id;
        $category->save();

        return to_route("categories");
    }

    public function delete_category($category_id){
        $category= Category::find($category_id);
        $category->delete();    
        return redirect()->back()->with("message","الفئة اتحذفت بنجاح");
    }
    public function products_view(){
        $products_pagination = Product::join('categories', 'products.cat_id', '=', 'categories.id')
        ->select('products.*', 'categories.name as category_name') 
        ->simplePaginate(30);

        $products = Product::join('categories', 'products.cat_id', '=', 'categories.id')
        ->select('products.*', 'categories.name as category_name') 
        ->get();

        $categories=Category::all();
        return view("products",["products"=>$products,"categories"=>$categories,"products_pagination"=>$products_pagination]);
    }
    public function add_product(Request $request)
    {

        $request->validate([
            "product_name" => "required|string|unique:products,name", 
            "number_of_pieces" => "required|integer|min:1", 
            "original_price" => "required|numeric|min:0",
            "packet_selling_price" => "required|numeric|min:0",
            "piece_selling_price" => "required|numeric|min:0",
            "number_of_exciting_packets" => "required|integer|min:0",
            "existing_number_of_pieces" => "required|integer|min:0",
            "selling_customer_piece_price" => "integer|min:0",
            "accept_pieces" => "required",
        ], [
            'required' => 'يجب ادخال الحقل',
            'product_name.unique' => 'اسم المنتج موجود فعلاً',
            'number_of_pieces' => 'عدد القطع اقل من 1',
            'original_price' => 'السعر اقل من 0',
            'packet_selling_price' => 'السعر اقل من 0',
            'piece_selling_price' => 'السعر اقل من 0',
            'number_of_exciting_packets' => 'عدد الكراتين اقل من 0',
            'existing_number_of_pieces' => 'عدد الكراتين اقل من 0',
            'selling_customer_piece_price.integer' => 'يجب إدخال رقم',
            'accept_pieces' => 'لم يتم تحديد هل المنتج يقبل التجزأة ام لا',
        ]);
        $product = new Product();
        $product->u_id = Auth::user()->id; 
        $product->cat_id = $request->category; 
        $product->name = $request->product_name;
        $product->n_pieces_in_packet = $request->number_of_pieces;
        $product->original_packet_price = $request->original_price;
        $product->selling_packet_price = $request->packet_selling_price;
        $product->piece_price = $request->piece_selling_price;
        $product->exicting_number_of_pieces = $request->number_of_exciting_packets;
        $product->existing_number_of_pieces = $request->existing_number_of_pieces;
        $product->accept_pieces = $request->accept_pieces;
        $product->selling_customer_piece_price = $request->selling_customer_piece_price;
        $product->save(); 
    
        return redirect()->route('products_view')
        ->with('message', 'تم إضافة المنتج بنجاح')
        ->withInput();   
    }

    public function delete_product($product_id){
        $product= Product::find($product_id);
        $product->delete();    
        return redirect()->back()->with("message","المنتج اتحذفت بنجاح");
    }
    







    public function update_product(Request $request, $product_id)
{
    try {
        $rules = [
            'type' => 'required|string|in:name,n_pieces_in_packet,original_packet_price,selling_packet_price,piece_price,exicting_number_of_pieces,existing_number_of_pieces,selling_customer_piece_price',
            'value' => 'required',
        ];

        $messages = [
            'value.required' => 'يجب إدخال قيمة',
            'value.unique' => 'القيمة موجودة فعلاً',
            'value.string' => 'يجب أن تكون القيمة نصية',
            'value.numeric' => 'يجب أن تكون القيمة ارقام',
        ];

        if ($request->type === 'name') {
            $rules['value'] .= '|string|unique:products,name';
        } else {
            $rules['value'] .= '|numeric|min:0';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::findOrFail($product_id);

        $field = $request->type;
        $product->{$field} = ($field === 'name') ? $request->value : intval($request->value);
        $product->save();

        return response()->json([
            'success' => true,
            'message' => "تم تعديل البيانات بنجاح",
            'product_id' => $product_id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء تعديل البيانات',
            'error' => $e->getMessage()
        ], 500);
    }
}




    public function exportProducts(){
        return (new FastExcel(Product::all()))->download('all_system_products.xlsx');
    }


    public function imports_view(){
        $products=Product::all();
        return view("imports_view",["products"=>$products]);
    }
    public function add_quantity(Request $request,$product_id){
        $request->validate([
            "packets_number"   => "numeric|min:0",
            "number_of_pieces" => "nullable|numeric|min:0"
        ], [
            "packets_number.numeric"  => "يجب إدخال قيمة عددية لعدد الكراتين.",
            "packets_number.min"      => "يجب إدخال عدد الكراتين كقيمة موجبة.",
        
            "number_of_pieces.numeric" => "يجب إدخال قيمة عددية لعدد القطع.",
            "number_of_pieces.min"     => "يجب إدخال عدد القطع كقيمة موجبة."
        ]);
        
        $product=Product::findOrFail($product_id);
        $old_number_of_packets=$product->exicting_number_of_pieces;
        $old_number_of_pieces=$product->existing_number_of_pieces;


        if($request->packets_number){
            $product->existing_number_of_pieces=$old_number_of_pieces+$request->number_of_pieces;
        }
        if($request->packets_number){
            $product->exicting_number_of_pieces=$old_number_of_packets+$request->packets_number;
        }
        $product->save();
        return to_route("imports_view")->with("message","تم إضافة الكمية بنجاح");
    }
}
