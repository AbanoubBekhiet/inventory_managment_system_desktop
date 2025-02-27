<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductBillRelation;
use App\Models\Bill;
use Rap2hpoutre\FastExcel\FastExcel;

class BillsController extends Controller
{
    public function make_bills(){
        $customers=Customer::all();
        $products=Product::all();
        return view("make_bills",["customers"=>$customers,"products"=>$products]);
    }

    public function bill_back(Request $request){
        $data = $request->all();
        $products = $request->json("products");
        $bill = new Bill;
        $bill->cus_id=$data["customer_id"];
        $bill->total_price=$data["total_bill_price"];
        $bill->u_id=Auth::user()->id;
        $bill->save();
        foreach ($products as $product) {
            $pro = Product::find($product['id']); 
                $pro->exicting_number_of_pieces -= $product['number_of_packets'];
                while($pro->existing_number_of_pieces<$product['number_of_pieces']){
                    $pro->existing_number_of_pieces += $pro-> n_pieces_in_packet;
                    $pro->exicting_number_of_pieces--;
                }
                $pro->existing_number_of_pieces -= $product['number_of_pieces'];
                $pro->save();
                ProductBillRelation::create([
                    'product_id' => $product['id'],
                    'bill_id' => $bill->id,
                    'number_of_packets' => $product["number_of_packets"],
                    'number_of_pieces' => $product["number_of_pieces"],
                    'packet_price' => $product["selling_packet_price"],
                    'piece_price' => $product["selling_piece_price"],
                    'total_product_price' => $product["total"]

                ]);
        }
        

        return response()->json([
            'message' => 'Products received successfully!',
            'data' => $data
        ], 200);
    
    }

    public function show_bills(){
        $bills_no_limit = DB::table("bills")
        ->join("customers","bills.cus_id","=","customers.id")
        ->select("bills.*","customers.cus_name","customers.phone_number")
        ->orderByDesc('created_at')->get();
        
        $bills_with_limit=DB::table("bills")
        ->join("customers","bills.cus_id","=","customers.id")
        ->select("bills.*","customers.cus_name","customers.phone_number")
        ->orderByDesc('created_at')->limit(100)->get();


        return view("show_bills",["bills_no_limit"=>$bills_no_limit,"bills_with_limit"=>$bills_with_limit]);
    }

    public function show_specific_bill($bill_id){
        $products = DB::table('customers')
            ->join('bills', 'customers.id', '=', 'bills.cus_id')
            ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
            ->join('products', 'products.id', '=', 'product_bill_relations.product_id')
            ->select('customers.*', 'bills.*', 'product_bill_relations.*','products.name','products.n_pieces_in_packet','products.selling_customer_piece_price')
            ->where("bills.id","=",$bill_id)
            ->get();
            // dd($products);
        return view("bill",["products"=>$products]);
    }


    public function statistics(){
        $total_number_of_money_based_on_original_price = DB::table("products")
        ->sum(DB::raw("(original_packet_price * exicting_number_of_pieces) + (piece_price * existing_number_of_pieces)"));
    
        $total_number_of_money_based_on_selling_price = DB::table("products")
        ->sum(DB::raw("(selling_packet_price * exicting_number_of_pieces) + (piece_price * existing_number_of_pieces)"));
    
        $total_number_of_money_based_on_original_price=number_format($total_number_of_money_based_on_original_price);
        $total_number_of_money_based_on_selling_price=number_format($total_number_of_money_based_on_selling_price);

        $total_selling_this_month = number_format(
            Bill::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_price')
        );
        $total_selling_this_year = number_format(
            Bill::whereYear('created_at', Carbon::now()->year)
                ->sum('total_price')
        );


        $total_selling_last_month = number_format(
            Bill::whereMonth('created_at', Carbon::now()->month == 1 ? 12 : Carbon::now()->month - 1)  
                ->whereYear('created_at',Carbon::now()->month == 1?Carbon::now()->year-1:Carbon::now()->year) 
                ->sum('total_price')
        );
        
        $total_selling_last_year = number_format(
            Bill::whereYear('created_at', Carbon::now()->year - 1)  
                ->sum('total_price')
        );






        $total_benefits_this_month = DB::table('bills')
        ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
        ->join('products', 'product_bill_relations.product_id', '=', 'products.id')
        ->select(
            DB::raw('SUM(bills.total_price) AS total_sales'),
            DB::raw('SUM(product_bill_relations.number_of_packets * products.original_packet_price) AS total_packet_original_price'),
            DB::raw('SUM(product_bill_relations.number_of_pieces * (products.original_packet_price / products.n_pieces_in_packet)) AS total_piece_original_price')
        )
        ->whereMonth('bills.created_at', Carbon::now()->month)
        ->whereYear('bills.created_at', Carbon::now()->year)
        ->first();
        
        $total_benefits_this_month_number=number_format($total_benefits_this_month->total_sales-($total_benefits_this_month->total_packet_original_price+$total_benefits_this_month->total_piece_original_price),2);
       
       
        $total_benefits_this_year = DB::table('bills')
        ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
        ->join('products', 'product_bill_relations.product_id', '=', 'products.id')
        ->select(
            DB::raw('SUM(bills.total_price) AS total_sales'),
            DB::raw('SUM(product_bill_relations.number_of_packets * products.original_packet_price) AS total_packet_original_price'),
            DB::raw('SUM(product_bill_relations.number_of_pieces * (products.original_packet_price / products.n_pieces_in_packet)) AS total_piece_original_price')
        )
        ->whereYear('bills.created_at', Carbon::now()->year)
        ->first();
        $total_benefits_this_year_number=number_format($total_benefits_this_year->total_sales-($total_benefits_this_year->total_packet_original_price+$total_benefits_this_year->total_piece_original_price),2);












        $total_benefits_last_month = DB::table('bills')
        ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
        ->join('products', 'product_bill_relations.product_id', '=', 'products.id')
        ->select(
            DB::raw('SUM(bills.total_price) AS total_sales'),
            DB::raw('SUM(product_bill_relations.number_of_packets * products.original_packet_price) AS total_packet_original_price'),
            DB::raw('SUM(product_bill_relations.number_of_pieces * (products.original_packet_price / products.n_pieces_in_packet)) AS total_piece_original_price')
        )
        ->whereMonth('bills.created_at', Carbon::now()->month==1?12:Carbon::now()->month-1)
        ->whereYear('bills.created_at', Carbon::now()->month==1?Carbon::now()->year-1:Carbon::now()->year)
        ->first();
        
        $total_benefits_last_month_number=number_format($total_benefits_last_month->total_sales-($total_benefits_last_month->total_packet_original_price+$total_benefits_last_month->total_piece_original_price),2);
        
        
        
        
        $total_benefits_last_year = DB::table('bills')
        ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
        ->join('products', 'product_bill_relations.product_id', '=', 'products.id')
        ->select(
            DB::raw('SUM(bills.total_price) AS total_sales'),
            DB::raw('SUM(product_bill_relations.number_of_packets * products.original_packet_price) AS total_packet_original_price'),
            DB::raw('SUM(product_bill_relations.number_of_pieces * (products.original_packet_price / products.n_pieces_in_packet)) AS total_piece_original_price')
        )
        ->whereYear('bills.created_at', Carbon::now()->year-1)
        ->first();
        $total_benefits_last_year_number=number_format($total_benefits_last_year->total_sales-($total_benefits_last_year->total_packet_original_price+$total_benefits_last_year->total_piece_original_price),2);













        $total_benefits_today = DB::table('bills')
        ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
        ->join('products', 'product_bill_relations.product_id', '=', 'products.id')
        ->select(
            DB::raw('SUM(bills.total_price) AS total_sales'),
            DB::raw('SUM(product_bill_relations.number_of_packets * products.original_packet_price) AS total_packet_original_price'),
            DB::raw('SUM(product_bill_relations.number_of_pieces * (products.original_packet_price / products.n_pieces_in_packet)) AS total_piece_original_price')
        )
        ->whereDay('bills.created_at', Carbon::now()->day)
        ->first();
        
        $total_benefits_today_number=number_format($total_benefits_today->total_sales-($total_benefits_today->total_packet_original_price+$total_benefits_today->total_piece_original_price),2);
       

        return view("statistics",
        [
            "total_money_based_on_original_price"=>$total_number_of_money_based_on_original_price,
            "total_money_based_on_selling_price"=>$total_number_of_money_based_on_selling_price,
            "total_selling_this_month"=>$total_selling_this_month,
            "total_selling_this_year"=>$total_selling_this_year,
            "total_selling_last_month"=>$total_selling_last_month,
            "total_selling_last_year"=>$total_selling_last_year,
            "total_benefits_this_month_number"=>$total_benefits_this_month_number,
            "total_benefits_this_year_number"=>$total_benefits_this_year_number,
            "total_benefits_last_month_number"=>$total_benefits_last_month_number,
            "total_benefits_last_year_number"=>$total_benefits_last_year_number,
            "total_benefits_today_number"=>$total_benefits_today_number,
            ]);
    }


    public function exportBills(){
        return (new FastExcel(Bill::all()))->download('all_system_bills.xlsx');
    }


    public function bill_binefits($bill_id){
        $total_benefits_specific_bill = DB::table('bills')
        ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
        ->join('products', 'product_bill_relations.product_id', '=', 'products.id')
        ->select(
            DB::raw('SUM(bills.total_price) AS total_sales'),
            DB::raw('SUM(product_bill_relations.number_of_packets * products.original_packet_price) AS total_packet_original_price'),
            DB::raw('SUM(product_bill_relations.number_of_pieces * (products.original_packet_price / products.n_pieces_in_packet)) AS total_piece_original_price')
        )
        ->where("bills.id","=",$bill_id)
        ->first();

        $total_benefits_specific_bill_number=number_format($total_benefits_specific_bill->total_sales-($total_benefits_specific_bill->total_packet_original_price+$total_benefits_specific_bill->total_piece_original_price),2);


        return view("bill_benifit",["total_benefits_specific_bill"=>$total_benefits_specific_bill_number]);
    }
}
