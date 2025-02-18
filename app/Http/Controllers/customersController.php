<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Bills;
use Illuminate\Support\facades\Auth;
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
        $customer= Customer::find($customer_id);
        $customer->delete();    
        return redirect()->back()->with("message","تم حذف العميل بنجاح");
    }

    // public function customer_purchases($customer_id){

    // }

}