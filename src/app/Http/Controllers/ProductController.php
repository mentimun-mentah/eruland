<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function add_product()
    {
        return view('products.add_product');
    }

    public function my_product()
    {
        return view('products.my_product');
    }

    public function detail_product($slug,$user_id)
    {
      $product = Product::with('user')->where('slug',$slug)->where('user_id',$user_id)->firstOrFail();
      $score = DB::table('products')->join('order_items','order_items.product_id','=','products.id')
        ->where('products.slug','=',$slug)
        ->where('products.user_id','=',$user_id)
        ->whereNotNull('order_items.rating')
        ->whereNotNull('order_items.review')
        ->select(
          DB::raw('(SUM(order_items.rating) * 5) / (COUNT(order_items.id) * 5) as score_value'),
          DB::raw('COUNT(order_items.id) as score_count')
        )
        ->get();

        return view('products.detail_product', ['product' => $product, 'score' => $score]);
    }

    public function getReviewProduct(Request $request, $product_id)
    {
      $results = DB::table('order_items')
        ->join('orders','orders.id', '=', 'order_items.order_id')
        ->join('users','users.id', '=', 'orders.user_id')
        ->where('order_items.product_id','=',$product_id)
        ->whereNotNull('order_items.rating')
        ->whereNotNull('order_items.review')
        ->select(
          'order_items.id as order_items_id', 'order_items.rating as order_items_rating', 'order_items.review as order_items_review',
          'order_items.updated_at as order_items_updated_at', 'users.name as users_name', 'users.avatar as users_avatar'
        )
        ->paginate(10);

        return $results;
    }

    public function changeMyProduct($id)
    {
      $product = Product::where('id',$id)->where('user_id',Auth::user()->id)->firstOrFail();
      return view('products.change_product', ['product' => $product]);
    }

    public function getCost(Request $request)
    {
        $request->validate([
          'origin' => 'required|integer|min:1',
          'originType' => 'required|string|in:city,subdistrict',
          'destination' => 'required|integer|min:1',
          'destinationType' => 'required|string|in:city,subdistrict',
          'weight' => 'required|integer|min:1',
          'courier' => 'required|string' 
        ]);

        $response = Http::asForm()->withHeaders([
          'key' => env('API_RAJAONGKIR')
        ])->post('https://pro.rajaongkir.com/api/cost', [
          'origin' => $request->origin,
          'originType' => $request->originType,
          'destination' => $request->destination,
          'destinationType' => $request->destinationType,
          'weight' => $request->weight,
          'courier' => $request->courier
        ]);

        $results = array();
        foreach($response->json()['rajaongkir']['results'] as $data1){
          $result = array();
          $result['code'] = $data1['code'];

          foreach($data1['costs'] as $data2){
            $result['service'] = $data2['service'];
            foreach($data2['cost'] as $data3){
              $result['value'] = $data3['value'];
              $result['etd'] = $data3['etd'];
            }
          }
          array_push($results,$result);
        }

        return $results;
    }

    public function getMyProduct(Request $request)
    {
        $query = Product::query();

        $query->where('user_id','=',Auth::user()->id);

        if($request->q) $query->where('name','like', '%'.$request->q.'%');
        if($request->order_by){
          $order_by = $request->order_by;
          if($order_by == 'newest') $query->orderBy('id','desc');
          if($order_by == 'oldest') $query->orderBy('id','asc');
          if($order_by == 'high_price') $query->orderBy('price','desc');
          if($order_by == 'low_price') $query->orderBy('price','asc');
        }

        $results = $query->paginate(9);

        foreach($results as $row){
          $score = DB::table('products')->join('order_items','order_items.product_id','=','products.id')
            ->where('products.id', '=', $row['id'])
            ->whereNotNull('order_items.rating')
            ->whereNotNull('order_items.review')
            ->select(
              DB::raw('(SUM(order_items.rating) * 5) / (COUNT(order_items.id) * 5) as score_value'),
              DB::raw('COUNT(order_items.id) as score_count')
            )
            ->get();

          $row->score = $score;
        }

        return $results;
    }

    public function allProducts(Request $request)
    {
        $query = Product::query();

        if($request->q) $query->where('name','like','%'.$request->q.'%');
        if($request->category) $query->where('category','=',$request->category);
        if($request->order_by){
          $order_by = $request->order_by;
          if($order_by == 'newest') $query->orderBy('id','desc');
          if($order_by == 'oldest') $query->orderBy('id','asc');
          if($order_by == 'high_price') $query->orderBy('price','desc');
          if($order_by == 'low_price') $query->orderBy('price','asc');
        }

        $results = $query->paginate(12);

        foreach($results as $row){
          $score = DB::table('products')->join('order_items','order_items.product_id','=','products.id')
            ->where('products.id', '=', $row['id'])
            ->whereNotNull('order_items.rating')
            ->whereNotNull('order_items.review')
            ->select(
              DB::raw('(SUM(order_items.rating) * 5) / (COUNT(order_items.id) * 5) as score_value'),
              DB::raw('COUNT(order_items.id) as score_count')
            )
            ->get();

          $row->score = $score;
        }

        return $results;
    }

    public function createProduct(Request $request)
    {
      $request->validate([
        'image' => 'required|mimes:jpg,jpeg,png|max:2000',
        'name' => 'required|string|min:3|max:255',
        'desc' => 'required|string|min:5',
        'category' => 'required|string|in:basah,kering',
        'stock' => 'required|integer|min:1',
        'price' => 'required|integer|min:1',
        'weight' => 'required|integer|min:1'
      ]);

      # save image to storage
      $photoName = Str::random(20).'.'.$request->image->getClientOriginalExtension();
      $request->image->move(public_path('storage/products/'),$photoName);

      Product::create([
        'image' => $photoName,
        'name' => $request->name,
        'slug' => Str::slug($request->name,'-'),
        'desc' => $request->desc,
        'category' => $request->category,
        'stock' => $request->stock,
        'price' => $request->price,
        'weight' => $request->weight,
        'user_id' => Auth::user()->id
      ]);

      return ['status' => 'Success create product'];
    }

    public function updateProduct(Request $request)
    {
      $product = Product::findOrFail($request->id);

      $request->validate([
        'image' => 'nullable|mimes:jpg,jpeg,png|max:2000',
        'name' => 'required|string|min:3|max:255',
        'desc' => 'required|string|min:5',
        'category' => 'required|string|in:basah,kering',
        'stock' => 'required|integer|min:1',
        'price' => 'required|integer|min:1',
        'weight' => 'required|integer|min:1'
      ]);

      $product->name = $request->name;
      $product->slug = Str::slug($request->name,'-');
      $product->desc = $request->desc;
      $product->category = $request->category;
      $product->stock = $request->stock;
      $product->price = $request->price;
      $product->weight = $request->weight;
      $product->save();

      # update image
      $imagePath = public_path('storage/products/'.$product->image);
      if($request->image && file_exists($imagePath)){
          unlink($imagePath);
          # save image to storage
          $photoName = Str::random(20).'.'.$request->image->getClientOriginalExtension();
          $request->image->move(public_path('storage/products/'),$photoName);
          $product->image = $photoName;
          $product->save();
      }

        toast('Success update product','success');
        return ['status' => 'Success update product'];
    }

    public function deleteProduct($id)
    {
      $product = Product::where('id',$id)->where('user_id',Auth::user()->id)->firstOrFail();

      # delete image
      $imagePath = public_path('storage/products/'.$product->image);
      if(file_exists($imagePath)) unlink($imagePath); 

      $product->delete();

      return ['status' => 'Success delete product'];
    }
}
