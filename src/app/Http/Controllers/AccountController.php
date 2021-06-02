<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OrderItem;
use App\Models\ShippingSubdistrict;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{

    public function profile()
    {
        return view('account.profile');
    }

    public function password()
    {
        return view('account.password');
    }

    public function orders()
    {
        return view('account.orders');
    }

    public function ordersIncoming()
    {
        return view('account.orders_incoming');
    }

    public function review()
    {
        return view('account.review');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
          'avatar' => 'required|mimes:jpg,jpeg,png|max:1000'
        ]);

        $user = User::findOrFail(Auth::user()->id);

        # delete image if user avatar not default.png
        $imagePath = public_path('storage/avatar/'.$user->avatar);
        if($user->avatar != 'default.png' && file_exists($imagePath)){
            unlink($imagePath); 
        }
        # save image to storage
        $imageName = Str::random(20).'.'.$request->avatar->getClientOriginalExtension();
        $request->avatar->move(public_path('storage/avatar/'),$imageName);
        # save image to db
        $user->avatar = $imageName;
        $user->save();

        return ['status' => 'Success update avatar'];
    }

    public function updateProfile(Request $request)
    {

      $request->validate([
        'name' => 'required|string|min:3|max:255',
        'fullname' => 'required|string|min:6|max:255',
        'phone' => 'required|min:10|max:20|regex:/(08)[0-9]{9}/',
        'gender' => 'required|in:laki-laki,perempuan',
        'address' => 'required|string|min:6',
        'code_pos' => 'required|integer|min:1',
        'shipping_subdistrict_id' => 'required|exists:shipping_subdistricts,id'
      ]);

      $user = User::findOrFail(Auth::user()->id);
      $user->name = $request->name;
      $user->fullname = $request->fullname;
      $user->phone = $request->phone;
      $user->gender = $request->gender;
      $user->address = $request->address;
      $user->code_pos = $request->code_pos;
      $user->shipping_subdistrict_id = $request->shipping_subdistrict_id;
      $user->save();

      return ['status' => 'Success update profile'];
    }

    public function updateRekening(Request $request)
    {
      $user = User::findOrFail(Auth::user()->id);

      $request->validate([
        'no_rek' => 'required|max:255|regex:/[0-9]/|unique:users,no_rek,'.$user->id,
        'a_n' => 'required|string|max:255'
      ]);

      $user->no_rek = $request->no_rek;
      $user->a_n = $request->a_n;
      $user->save();

      return ['status' => 'Success update rekening'];
    }

    public function updatePassword(Request $request)
    {

      $request->validate([
          'old_password' => 'required|string|min:8|max:255',
          'password' => 'required|string|min:8|max:255|confirmed'
      ]);

      $user = User::findOrFail(Auth::user()->id);

      if(!Hash::check($request->old_password,$user->password)){
        return response()->json(['errors' => ['old_password' => ['The old password does not match with our record.']]],422);
      }

      $user->password = Hash::make($request->password);
      $user->save();

      return ['status' => 'Success update password'];

    }

    public function setReview(Request $request, $id)
    {
        $request->validate([
          'rating' => 'required|integer|min:1|max:5',
          'review' => 'required|string|min:5'
        ]);

        $order_item = OrderItem::findOrFail($id);

        $order_item->rating = $request->rating;
        $order_item->review = $request->review;
        $order_item->touch();
        $order_item->save();

        return ['status' => 'Success set review'];
    }

    public function setResi(Request $request, $order_id)
    {
      $request->validate([
        'no_resi' => 'required|string|min:3'
      ]);

      $order_items = DB::table('order_items')->where('order_items.order_id', $order_id)->where('order_items.penjual_id','=', Auth::user()->id)->get();

      foreach($order_items as $order_item){
        $order = OrderItem::findOrFail($order_item->id);
        $order->no_resi = $request->no_resi;
        $order->save();        
      }

        return ['status' => 'Success set no_resi'];
    }

    public function getCityDistrictById($id)
    {
      $results = DB::table('shipping_provinces')->join('shipping_cities','shipping_cities.shipping_province_id', '=', 'shipping_provinces.id')
          ->join('shipping_subdistricts', 'shipping_subdistricts.shipping_city_id', '=', 'shipping_cities.id')
          ->where(function($query) use ($id) {
              $query->where('shipping_subdistricts.id', '=', $id);
          })
          ->select(
            'shipping_provinces.name as shipping_provinces_name',
            'shipping_cities.id as shipping_cities_id',
            'shipping_cities.name as shipping_cities_name',
            'shipping_cities.type as shipping_cities_type',
            'shipping_subdistricts.id as shipping_subdistricts_id',
            'shipping_subdistricts.name as shipping_subdistricts_name'
          )->get();

      return $results;
    }

    public function getCityDistrict(Request $request)
    {
      $q = $request->query('q');
      $results = DB::table('shipping_provinces')->join('shipping_cities','shipping_cities.shipping_province_id', '=', 'shipping_provinces.id')
          ->join('shipping_subdistricts', 'shipping_subdistricts.shipping_city_id', '=', 'shipping_cities.id')
          ->where(function($query) use ($q) {
              $query->where('shipping_cities.name','like','%'.$q.'%');
          })
          ->orWhere(function($query) use ($q) {
              $query->where('shipping_subdistricts.name','like','%'.$q.'%');
          })
          ->select(
            'shipping_provinces.name as shipping_provinces_name',
            'shipping_cities.id as shipping_cities_id',
            'shipping_cities.name as shipping_cities_name',
            'shipping_cities.type as shipping_cities_type',
            'shipping_subdistricts.id as shipping_subdistricts_id',
            'shipping_subdistricts.name as shipping_subdistricts_name'
          )->get();

      return $results;
    }

    public function getOrderUser(Request $request)
    {
      $status = $request->query('status');

      $results = DB::table('orders')
        ->where('orders.user_id','=',Auth::user()->id)
        ->where('orders.transaction_status','=',$status)                                  
        ->orderBy('orders.id','desc')
        ->select(
          'orders.id as orders_id', 'orders.bank as orders_bank', 'orders.gross_amount as orders_gross_amount',
          'orders.total_cost_shipping as orders_total_cost_shipping', 'orders.order_id as orders_order_id',
          'orders.payment_type as orders_payment_type', 'orders.pdf_url as orders_pdf_url', 'orders.transaction_status as orders_transaction_status',
          'orders.va_number as orders_va_number', 'orders.user_id as orders_user_id', 'orders.created_at as orders_created_at',
          'orders.updated_at as orders_updated_at'
        )
        ->paginate(5);

      foreach($results as $data){
        $items = DB::table('order_items')
          ->join('products','products.id', '=', 'order_items.product_id')
          ->where('order_items.order_id', '=', $data->orders_id)
          ->orderBy('order_items.penjual_id','asc')
          ->select(
            'products.image as product_images', 'products.name as products_name', 'products.weight as products_weight',
            'order_items.id as order_items_id', 'order_items.qty as order_items_qty', 'order_items.price as order_items_price',
            'order_items.code_shipping as order_items_code_shipping', 'order_items.service_shipping as order_items_service_shipping',
            'order_items.cost_shipping as order_items_cost_shipping',
            'order_items.etd_shipping as order_items_etd_shipping', 'order_items.no_resi as order_items_no_resi', 'order_items.order_id as order_items_order_id',
            'order_items.penjual_id as order_items_penjual_id', 'order_items.product_id as order_items_product_id',
            'order_items.created_at as order_items_created_at', 'order_items.updated_at as order_items_updated_at'
          )
          ->get();

        $data->order_items = $items;
      }

      return $results;
    }

    public function getOrderPenjual(Request $request)
    {
      $orders_id = DB::table('order_items')->where('order_items.penjual_id','=',Auth::user()->id)->select('order_items.order_id')->distinct()->get()->toArray();

      $ordersIn = array();
      foreach($orders_id as $order_id) $ordersIn[] = $order_id->order_id;

      $results = DB::table('orders')
        ->join('users','users.id','=','orders.user_id')
        ->whereIn('orders.id', $ordersIn)
        ->where('orders.transaction_status','=','success')
        ->orderBy('orders.id','desc')
        ->select(
          'users.fullname as users_fullname', 'users.email as users_email', 'users.phone as users_phone',
          'users.shipping_subdistrict_id as users_shipping_subdistrict_id', 'users.code_pos as users_code_pos', 'users.address as users_address',
          'orders.id as orders_id', 'orders.bank as orders_bank', 'orders.order_id as orders_order_id',
          'orders.payment_type as orders_payment_type', 'orders.pdf_url as orders_pdf_url', 'orders.transaction_status as orders_transaction_status',
          'orders.va_number as orders_va_number','orders.user_id as orders_user_id', 'orders.created_at as orders_created_at',
          'orders.updated_at as orders_updated_at'
        )
        ->paginate(5);

      foreach($results as $data){
        $items = DB::table('order_items')
          ->join('products','products.id', '=', 'order_items.product_id')
          ->where('order_items.order_id', '=', $data->orders_id)
          ->where('order_items.penjual_id','=',Auth::user()->id)
          ->select(
            'products.image as product_images', 'products.name as products_name', 'products.weight as products_weight',
            'order_items.id as order_items_id', 'order_items.qty as order_items_qty', 'order_items.price as order_items_price',
            'order_items.code_shipping as order_items_code_shipping',
            'order_items.service_shipping as order_items_service_shipping', 'order_items.cost_shipping as order_items_cost_shipping',
            'order_items.etd_shipping as order_items_etd_shipping', 'order_items.no_resi as order_items_no_resi', 'order_items.drawn as order_items_drawn',
            'order_items.order_id as order_items_order_id',
            'order_items.penjual_id as order_items_penjual_id', 'order_items.product_id as order_items_product_id',
            'order_items.created_at as order_items_created_at', 'order_items.updated_at as order_items_updated_at'
          )
          ->get();

        $data->order_items = $items;
      }

      return $results;
    }

    public function waitingReview(Request $request)
    {
      $results = DB::table('order_items')
        ->join('products','products.id', '=', 'order_items.product_id')
        ->join('orders','orders.id', '=', 'order_items.order_id')
        ->whereNull('order_items.rating')
        ->whereNull('order_items.review')
        ->where('orders.transaction_status','=','success')
        ->where('orders.user_id', '=', Auth::user()->id)
        ->orderBy('order_items.id','desc')
        ->select(
          'order_items.id as order_items_id', 'order_items.rating as order_items_rating' , 'order_items.review as order_items_review',
          'orders.order_id as orders_order_id', 'orders.created_at as orders_created_at',
          'products.name as products_name', 'products.image as products_image',
        )
        ->paginate(5);      

      return $results;
    }

    public function myReview(Request $request)
    {
      $results = DB::table('order_items')
        ->join('products','products.id', '=', 'order_items.product_id')
        ->join('orders','orders.id', '=', 'order_items.order_id')
        ->whereNotNull('order_items.rating')
        ->whereNotNull('order_items.review')
        ->where('orders.transaction_status','=','success')
        ->where('orders.user_id', '=', Auth::user()->id)
        ->orderBy('order_items.id','desc')
        ->select(
          'order_items.id as order_items_id', 'order_items.rating as order_items_rating' , 'order_items.review as order_items_review',
          'order_items.updated_at as order_items_updated_at', 'orders.order_id as orders_order_id', 'orders.created_at as orders_created_at',
          'products.name as products_name', 'products.image as products_image',
        )
        ->paginate(5);      

      return $results;
    }

    public function myBalance()
    {
      $results = DB::table('order_items')
        ->join('orders','orders.id','=','order_items.order_id')
        ->where('order_items.penjual_id','=',Auth::user()->id)
        ->where('order_items.drawn','=','no')
        ->where('orders.transaction_status','=','success')
        ->whereNotNull('order_items.no_resi')
        ->select('order_items.qty as order_items_qty', 'order_items.price as order_items_price')
        ->get();

      $total = 0;
      foreach($results as $data)
        $total += $data->order_items_price * $data->order_items_qty;

      return $total;
    }

    public function drawnMyBalance()
    {
      $balance = $this->myBalance();

      if($balance < 100000) 
        return response()->json(['errors' => ['balance' => ['Saldo kamu belum mencukupi :(']]],422);

      $results = DB::table('order_items')
        ->join('orders','orders.id','=','order_items.order_id')
        ->where('order_items.penjual_id','=',Auth::user()->id)
        ->where('order_items.drawn','=','no')
        ->where('orders.transaction_status','=','success')
        ->whereNotNull('order_items.no_resi')
        ->select('order_items.id')
        ->get();

      foreach($results as $data){
        $order = OrderItem::findOrFail($data->id);
        $order->drawn = 'yes';
        $order->save();        
      }

      Withdrawal::create([
        'nominal' => $balance - 10000,
        'user_id' => Auth::user()->id
      ]);

      return ['status' => 'Success drawn balance'];
    }

    public function myWithdrawals()
    {
      $results = DB::table('withdrawals')
        ->where('withdrawals.user_id','=',Auth::user()->id)
        ->orderBy('withdrawals.id','desc')
        ->get();

      return $results;
    }
}
