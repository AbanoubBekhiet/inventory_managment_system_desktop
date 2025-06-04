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
                $original_piece_price=number_format($pro->original_packet_price/$pro->n_pieces_in_packet,2);
                ProductBillRelation::create([
                    'product_id' => $product['id'],
                    'bill_id' => $bill->id,
                    'number_of_packets' => $product["number_of_packets"],
                    'number_of_pieces' => $product["number_of_pieces"],
                    'packet_price' => $product["selling_packet_price"],
                    'piece_price' => $product["selling_piece_price"],
                    'original_packet_price' => $pro->original_packet_price,
                    'original_peice_price' => $original_piece_price,
                    'total_product_price' => $product["total"]
                ]);
                $pro->save();

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
        ->select("bills.*","customers.cus_name","customers.phone_number","bills.discount")
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
        return view("bill",["products"=>$products]);
    }


    public function statistics(){
        $total_number_of_money_based_on_original_price = DB::table("products")
        ->sum(DB::raw("(original_packet_price * exicting_number_of_pieces) + ((original_packet_price/n_pieces_in_packet) * existing_number_of_pieces)"));
    
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

/////////////////////////////////////////////////////////////////////


        $total_benefits_this_month = ProductBillRelation::query()
        ->selectRaw('
            SUM(
                total_product_price - 
                (COALESCE(number_of_packets, 0) * COALESCE(original_packet_price, 0) + 
                 COALESCE(number_of_pieces, 0) * COALESCE(original_peice_price, 0))
            ) AS total_benefit
        ')
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->value('total_benefit');
        
        $total_discounts1 = Bill::whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->sum('discount');

        $total_benefits_this_month_number=number_format($total_benefits_this_month-$total_discounts1,2);

/////////////////////////////////////////////////////////////////////

 $total_benefits_this_year = ProductBillRelation::query()
    ->selectRaw('
        SUM(
            total_product_price - 
            (COALESCE(number_of_packets, 0) * COALESCE(original_packet_price, 0) + 
             COALESCE(number_of_pieces, 0) * COALESCE(original_peice_price, 0))
        ) AS total_benefit
    ')
    ->whereBetween('created_at', [
        Carbon::now()->startOfYear(),
        Carbon::now()
    ])
    ->value('total_benefit') ?? 0;

$total_discounts2 = Bill::whereBetween('created_at', [
    Carbon::now()->startOfYear(),
    Carbon::now()
])
    ->sum('discount') ?? 0;

$total_benefits_this_year_number = number_format($total_benefits_this_year - $total_discounts2, 2);
/////////////////////////////////////////////////////////////////////
$lastMonth = Carbon::now()->subMonth();
$total_benefits_last_month = ProductBillRelation::query()
    ->selectRaw('
        SUM(
            total_product_price - 
            (COALESCE(number_of_packets, 0) * COALESCE(original_packet_price, 0) + 
             COALESCE(number_of_pieces, 0) * COALESCE(original_peice_price, 0))
        ) AS total_benefit
    ')
    ->whereYear('created_at', $lastMonth->year)
    ->whereMonth('created_at', $lastMonth->month)
    ->value('total_benefit') ?? 0;

$total_discounts3 = Bill::whereYear('created_at', $lastMonth->year)
    ->whereMonth('created_at', $lastMonth->month)
    ->sum('discount') ?? 0;

$total_benefits_last_month_number = number_format($total_benefits_last_month - $total_discounts3, 2);
        /////////////////////////////////////////////////////////////////////        
        
        $total_benefits_last_year = ProductBillRelation::query()
            ->selectRaw('
                SUM(
                    total_product_price - 
                    (COALESCE(number_of_packets, 0) * COALESCE(original_packet_price, 0) + 
                    COALESCE(number_of_pieces, 0) * COALESCE(original_peice_price, 0))
                ) AS total_benefit
            ')
            ->whereBetween('created_at', [
                Carbon::create(Carbon::now()->year - 1, 1, 1)->startOfYear(),
                Carbon::create(Carbon::now()->year - 1, 12, 31)->endOfYear()
            ])
            ->value('total_benefit') ?? 0;

        $total_discounts4 = Bill::whereBetween('created_at', [
            Carbon::create(Carbon::now()->year - 1, 1, 1)->startOfYear(),
            Carbon::create(Carbon::now()->year - 1, 12, 31)->endOfYear()
        ])
            ->sum('discount') ?? 0;

        $total_benefits_last_year_number = number_format($total_benefits_last_year - $total_discounts4, 2);


    

        /////////////////////////////////////////////////////////////////////

            $total_benefits_today = ProductBillRelation::query()
                ->selectRaw('
                    SUM(
                        total_product_price - 
                        (COALESCE(number_of_packets, 0) * COALESCE(original_packet_price, 0) + 
                        COALESCE(number_of_pieces, 0) * COALESCE(original_peice_price, 0))
                    ) AS total_benefit
                ')
                ->whereDate('created_at', Carbon::today())
                ->value('total_benefit') ?? 0;

            $total_discounts5 = Bill::whereDate('created_at', Carbon::today())->sum('discount') ?? 0;

            $total_benefits_today_number = number_format($total_benefits_today - $total_discounts5, 2);


        /////////////////////////////////////////////////////////////////////       

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
        $total_benefits_specific_bill = DB::table('product_bill_relations')
        ->join('bills', 'product_bill_relations.bill_id', '=', 'bills.id')
        ->select('product_bill_relations.number_of_packets'
        ,'product_bill_relations.number_of_pieces'
        ,'product_bill_relations.original_packet_price'
        ,'product_bill_relations.original_peice_price'
        ,'bills.total_price'
        ,'bills.discount'
        )
        ->where("bills.id","=",$bill_id)
        ->get();
        
        $total_products_price=0;
        foreach($total_benefits_specific_bill as $index){
            $total_products_price+=($index->number_of_packets*$index->original_packet_price+$index->number_of_pieces*$index->original_peice_price);
        }
        $total_benefits_specific_bill_number=number_format(($total_benefits_specific_bill[0]->total_price)-$total_products_price-$total_benefits_specific_bill[0]->discount,2);
        return view("bill_benifit",["total_benefits_specific_bill"=>$total_benefits_specific_bill_number]);
    }


    public function discount(Request $request,$bill_id){
        $bill=Bill::find($bill_id,"id");
        $bill->discount=$request->discount;
        $bill->save();

        return to_route("show_bills")->with("message","تم إضافة الخصم بنجاح");
    }

}
