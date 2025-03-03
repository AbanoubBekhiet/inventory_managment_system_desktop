<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\ProductBillRelation;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\facades\Auth;
use Illuminate\Support\facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\facades\Validator;

class customersController extends Controller
{
    public function customers(){
        $customers=Customer::get();
        return view("customers",["customers"=>$customers]);
    }
    public function add_customer(Request $request){
        $request->validate([
            "address"=>"required|string",
            "cus_name"=>"required|string|unique:customers,cus_name",
            "phone_number"=>"required|string|unique:customers,phone_number",
        ],[
            "address"=>"يجب إدخال عنوان المستخدم",
            "cus_name.required"=>"يجب إدخال اسم المستخدم",
            "cus_name.unique"=>"اسم المستخدم موجود فعلاً",
            "phone_number.required"=>"يجب إدخال رقم الهاتف",
            "phone_number.unique"=>"رقم الهاتف موجود فعلاً",
        ]);

        $customer=new Customer();
        $customer->u_id=Auth::user()->id;
        $customer->cus_name=$request->cus_name;
        $customer->address=$request->address;
        $customer->phone_number=$request->phone_number;
        $customer->save();

        return to_route("customers")->with("message","تم إضافة الزبون بنجاح");
    }








    public function update_customer(Request $request, $customer_id) {
        try {
            $rules = [
                'type' => 'required|string|in:cus_name,address,phone_number',
                'value' => 'required|string',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($request->type === 'cus_name') {
                $validator->sometimes('value', 'required|string|unique:customers,cus_name', function ($input) {
                    return true;
                });
            } elseif ($request->type === 'phone_number') {
                $validator->sometimes('value', 'required|numeric|unique:customers,phone_number', function ($input) {
                    return true;
                });
            }
    
            $messages = [
                'value.required' => 'يجب إدخال قيمة',
                'value.unique' => 'القيمة موجودة فعلاً',
                'value.string' => 'يجب أن تكون القيمة نصية',
                'value.numeric' => 'يجب أن تكون القيمة ارقام',
            ];
    
            $validator->setCustomMessages($messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422); 
            }
    
            $customer = Customer::findOrFail($customer_id);
    
            $field = $request->type;
            $customer->$field = $request->value;
            $customer->save();
    
            return response()->json([
                'success' => true,
                'message' => "تم تعديل البيانات بنجاح",
                'customer_id' => $customer_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    public function delete_customer($customer_id){
        $num_of_bills=Bill::where("cus_id","=",$customer_id)->first();
        if($num_of_bills){
            return redirect()->back()->with("message"," العميل لديه فواتير لايمكن حذفه");
        }
        else{
            $customer= Customer::findOrFail($customer_id);
            $customer->delete();    
            return redirect()->back()->with("message","تم حذف العميل بنجاح");
        }
    }





    public function exporCustomers(){
        return (new FastExcel(Customer::all()))->download('all_system_customers.xlsx');
    }





    public function customer_purchases($customer_id){
        

        $total_selling_this_month = number_format(
            Bill::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->where("cus_id","=",$customer_id)
                ->sum('total_price')
        );
        $total_selling_this_year = number_format(
            Bill::whereYear('created_at', Carbon::now()->year)
            ->where("cus_id","=",$customer_id)
                ->sum('total_price')

        );


        $total_selling_last_month = number_format(
            Bill::whereMonth('created_at', Carbon::now()->month == 1 ? 12 : Carbon::now()->month - 1)  
                ->whereYear('created_at',Carbon::now()->month == 1?Carbon::now()->year-1:Carbon::now()->year) 
                ->where("cus_id","=",$customer_id)
                ->sum('total_price')

        );
        
        $total_selling_last_year = number_format(
            Bill::whereYear('created_at', Carbon::now()->year - 1)  
            ->where("cus_id","=",$customer_id)
                ->sum('total_price')

        );



/////////////////////////////////////////////////////////

        $total_benefits_this_month = DB::table('bills')
        ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
        ->selectRaw('
        SUM(
            total_product_price - 
            (COALESCE(number_of_packets, 0) * COALESCE(original_packet_price, 0) + 
            COALESCE(number_of_pieces, 0) * COALESCE(original_peice_price, 0))
        ) AS total_benefit
        ')
        ->whereMonth('bills.created_at', Carbon::now()->month)
        ->whereYear('bills.created_at', Carbon::now()->year)
        ->where("bills.cus_id","=",$customer_id)
        ->first();
/////////////////////////////////////////////////////////
        
        $total_benefits_this_year = DB::table('bills')
        ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
        ->selectRaw('
        SUM(
            total_product_price - 
            (COALESCE(number_of_packets, 0) * COALESCE(original_packet_price, 0) + 
            COALESCE(number_of_pieces, 0) * COALESCE(original_peice_price, 0))
        ) AS total_benefit
        ')
        ->whereYear('bills.created_at', Carbon::now()->year)
        ->where("bills.cus_id","=",$customer_id)
        ->first();




/////////////////////////////////////////////////////////


        $total_benefits_last_month = DB::table('bills')
        ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
        ->selectRaw('
        SUM(
            total_product_price - 
            (COALESCE(number_of_packets, 0) * COALESCE(original_packet_price, 0) + 
            COALESCE(number_of_pieces, 0) * COALESCE(original_peice_price, 0))
        ) AS total_benefit
        ')
        ->whereMonth('bills.created_at', Carbon::now()->month==1?12:Carbon::now()->month-1)
        ->whereYear('bills.created_at', Carbon::now()->month==1?Carbon::now()->year-1:Carbon::now()->year)
        ->where("bills.cus_id","=",$customer_id)
        ->first();

/////////////////////////////////////////////////////////
        

        $total_benefits_last_year = DB::table('bills')
        ->join('product_bill_relations', 'bills.id', '=', 'product_bill_relations.bill_id')
        ->selectRaw('
        SUM(
            total_product_price - 
            (COALESCE(number_of_packets, 0) * COALESCE(original_packet_price, 0) + 
            COALESCE(number_of_pieces, 0) * COALESCE(original_peice_price, 0))
        ) AS total_benefit
        ')
        ->whereYear('bills.created_at', Carbon::now()->year-1)
        ->where("bills.cus_id","=",$customer_id)
        ->first();



        /////////////////////////////////////////////////////////

        
        $bills = DB::table("bills")
        ->join("customers","bills.cus_id","=","customers.id")
        ->select("bills.*","customers.cus_name","customers.phone_number")
        ->orderByDesc('created_at')
        ->where("cus_id","=",$customer_id)
        ->paginate(100);
/////////////////////////////////////////////////////////


        return view("customer_purchases",
        [
            "total_selling_this_month"=>$total_selling_this_month,
            "total_selling_this_year"=>$total_selling_this_year,
            "total_selling_last_month"=>$total_selling_last_month,
            "total_selling_last_year"=>$total_selling_last_year,
            "total_benefits_this_month_number"=>$total_benefits_this_month,
            "total_benefits_this_year_number"=>$total_benefits_this_year,
            "total_benefits_last_month_number"=>$total_benefits_last_month,
            "total_benefits_last_year_number"=>$total_benefits_last_year,
            "bills"=>$bills,
            ]);
    }

}