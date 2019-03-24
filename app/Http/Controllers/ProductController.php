<?php

namespace App\Http\Controllers;
use App\User;
use App\Product;
use Illuminate\Http\Request;
use App\Notifications\ProductNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $product = $user->products;

        foreach($product as $key => $value){
            $product[$key]['url'] = url('product_image') . '/' . $product[$key]['product_image'];
        }

        // $notify = auth()->user()->unreadNotifications->count();
        // $product['notify'] = auth()->user()->unreadNotifications->count();
        // return Response()->json($product);

        // return ProductResource::collection($product);
        
        return new ProductResource($product);
    }

    public function productNotify()
    {
        // $notify = auth()->user()->unreadNotifications->count();
        $user_id = auth()->user()->id;

        $user = User::find($user_id);

        $notify = $user->notifications;

        return Response()->json($notify);

    }

    // public function AllProduct()
    // {
    //     $product = Product::all();

    //     foreach($product as $key => $value){
    //         $product[$key]['url'] = url('product_image') . '/' . $product[$key]['product_image'];
    //     }

    //     return response()->json($product);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function store(Request $request)
    {   
        $this->validate($request,[
            'product_name' => 'required',
            'product_description' => 'required',
            'product_price'=> 'required',
            'product_image'=>'image|nullable|max:99999999999999'
        ]);
        
        if($request->hasFile('product_image')){
            //get filename with the extension
            $filenameWithExt = $request->file('product_image')->getClientOriginalName();
            //get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('product_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            // $path = $request->file('product_image')->storeAs('public/product_image',$fileNameToStore);
            Storage::disk('public')->put($fileNameToStore, File::get($request->file('product_image')));

        }else{
            $fileNameToStore = 'gundum.jpg';
        }
        
        $product = new Product;
        $product->product_name = $request->input('product_name');
        $product->product_description = $request->input('product_description');
        $product->user_id = auth()->user()->id;
        $product->product_price = $request->input('product_price');
        $product->product_image = $fileNameToStore;
        
        $product->save();

        $user =  User::where('id','!=', auth()->user()->id)->get();

        if(\Notification::send($user,new ProductNotification(Product::latest('id')->first())))
        {
            return back();
            
        }

        return Response()->json('success upload');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product = Product::findorFail($product);
        
        return Response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product = Product::findorFail($product)->first();

        return Response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        $data = $request->all();

        // return response()->json($data);die;

        // $this->validate($request,[
        //     'product_name' => 'required',
        //     'product_description' => 'required',
        //     'product_price' => 'required',
        //     'product_image'=>'image|nullable|max:99999999999999'
        // ]);

        if($request->hasFile('product_image')){
            //get filename with the extension
            $filenameWithExt = $request->file('product_image')->getClientOriginalName();
            //get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('product_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            // $path = $request->file('product_image')->move('public/product_image',$fileNameToStore);
            Storage::disk('public')->put($fileNameToStore, File::get($request->file('product_image')));

        }else{
            $fileNameToStore = 'gundum.jpg';
        }

        Product::where('id',$product)
                    ->update([
                        'product_name' => $data['product_name'],
                        'product_price' => $data['product_price'],
                        'product_description' => $data['product_description'],
                        'product_image' => $fileNameToStore,
                    ]);
        
        return Response()->json(' Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $productItem = Product::findorFail($product)->first();

        $productItem->delete();

        $file = $productItem->product_image;
 
        Storage::disk('public')->delete($file);

        return Response()->json('successfully deleted');
    }

    public function searchProduct(Product $product)
    {
        // $word = '%' . $data . '%';
        // $product = Product::all()
        // ->where('product_name','like',$word)
        // ->get();
        // $result = Product::where($filter, 1)->get()->toArray();
        // $word = '%' . $product . '%';
        // $data = Product::select
        // where('product_name','LIKE',$word)
        // ->get();

        $data = DB::table('products')
        ->select('*')
        ->where('product_name','Like','%'.$product.'%')
        ->get();

        return Response()->json($data);
    }
}

// $builder = $builder->where('description', 'like', $keyword);