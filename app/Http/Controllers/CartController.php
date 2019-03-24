<?php

namespace App\Http\Controllers;

use App\Cart;
use App\User;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Notifications\ProductNotification;

class CartController extends Controller
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
        $cart = $user->carts;

        foreach($cart as $key => $value){
            $cart[$key]['url'] = url('product_image') . '/' . $cart[$key]['product_image'];
        }

        return response()->json($cart);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_id = $request->input('product_id');

        $productList = Cart::where('product_id',$product_id)->get()->count();

        if($productList){
            $msg = 'all ready exist!';
            return response()->json($msg);
        }else{

            $data = Cart::create([
            
                'product_name'=> $request->input('product_name'),
                'product_price'=> $request->input('product_price'),
                'user_id'=> auth()->user()->id,
                'product_id' => $request->input('product_id'),
                'product_image'=> $request->input('product_image'),
                'quantity'=>1
            ]);

            if($data){
                return response()->json('success');
            }
            else{
                return response()->json('error');
            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {

        $cartList = Cart::find($cart)->first();

        $cartList->delete();

        // $file = $cartList->product_image;
 
        // Storage::disk('public')->delete($file);

        return response()->json('success');
    }
}
