<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $products = Product::all();

        return view('frontend.pages.home',compact('products'));
    }

    public function product(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->get();
        } else {
            $products = Product::all();
        }
        return view('frontend.pages.product', compact('products', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function details($slug)
    {

        $data['product'] = Product::with('images')->where('slug', $slug)->firstOrFail();

        return view('frontend.components.product.details',$data);
    }


    public function latestProduct(){

        $data['latestProducts'] = Product::latest()->take(12)->get();

        return view('frontend.new_arrival.index',$data);
    }

}
